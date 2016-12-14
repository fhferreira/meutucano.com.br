<?php namespace Core\Http\Controllers\Relatorio;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Pedido\Nota;
use App\Models\Pedido\Nota\Devolucao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use NFePHP\Extras\Danfe;
use App\Http\Controllers\Rest\RestControllerTrait;
use Core\Models\Pedido\Nota;

/**
 * Class ICMSController
 * @package Core\Http\Controllers\Relatorio
 */
class ICMSController extends Controller
{
    use RestControllerTrait;

    protected $validationRules = [];

    /**
     * Return relatório ICMS
     */
    public function icms()
    {
        $notas = Nota::with(['pedido', 'pedido.imposto', 'pedido.endereco', 'pedido.produtos', 'pedido.produtos.produto'])
            ->where(DB::raw('MONTH(pedido_notas.data)'), '=', date("m", strtotime("-1 month")))
            ->where(DB::raw('YEAR(pedido_notas.data)'), '=', date('Y'))
            ->get();

        $devolucoes = Devolucao::with(['nota', 'nota.pedido', 'nota.pedido.imposto', 'nota.pedido.endereco', 'nota.pedido.produtos', 'nota.pedido.produtos.produto'])
            ->where(DB::raw('MONTH(pedido_nota_devolucoes.data)'), '=', date("m", strtotime("-1 month")))
            ->where(DB::raw('YEAR(pedido_nota_devolucoes.data)'), '=', date('Y'))
            ->get();

        $relatorio[] = 'OPERACAO;TIPO;NUMERO;UFREM;ICMSREM;UFDEST;ICMSDEST;TOTAL;SEMINOVO';
        $relatorio[] = "\r\n";
        foreach ($notas as $nota) {
            $relatorio[] = $nota->pedido->operacao . ';';
            $relatorio[] = 'VENDA;';
            $relatorio[] = $nota->numero . ';';
            $relatorio[] = \Config::get('core.uf'). ';';
            $relatorio[] = 'R$' . (count($nota->pedido->imposto) ? number_format($nota->pedido->imposto->icms_remetente, 2, ',', '.') : '0,00') . ';';
            $relatorio[] = $nota->pedido->endereco->uf . ';';
            $relatorio[] = 'R$' . (count($nota->pedido->imposto) ? number_format($nota->pedido->imposto->icms_destinatario, 2, ',', '.') : '0,00') . ';';
            $relatorio[] = 'R$' . number_format(abs($nota->pedido->total), 2, ',', '.') . ';';

            $produtoString = [];
            foreach ($nota->pedido->produtos as $produto) {
                $produtoString[] = ($produto->produto->estado == 0) ? 'NOVO' : 'SEMINOVO';
            }

            $relatorio[] = implode(',', $produtoString);
            $relatorio[] = "\r\n";
        }

        foreach ($devolucoes as $dev) {
            $relatorio[] = (($dev->nota->pedido->endereco->uf == 'SC') ? '1202' : '2202') . ';';
            $relatorio[] = 'DEVOLUCAO;';
            $relatorio[] = $dev->nota->numero . ';';
            $relatorio[] = \Config::get('tucano.uf'). ';';
            $relatorio[] = 'R$' . (count($dev->nota->pedido->imposto) ? number_format($dev->nota->pedido->imposto->icms_remetente, 2, ',', '.') : '0,00') . ';';
            $relatorio[] = $dev->nota->pedido->endereco->uf . ';';
            $relatorio[] = 'R$' . (count($dev->nota->pedido->imposto) ? number_format($dev->nota->pedido->imposto->icms_destinatario, 2, ',', '.') : '0,00') . ';';
            $relatorio[] = 'R$' . number_format(abs($dev->nota->pedido->total), 2, ',', '.') . ';';

            $produtoString = [];
            foreach ($dev->nota->pedido->produtos as $produto) {
                $produtoString[] = ($produto->produto->estado == 0) ? 'NOVO' : 'SEMINOVO';
            }

            $relatorio[] = implode(',', $produtoString);
            $relatorio[] = "\r\n";
        }

        $output = implode('', $relatorio);

        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="icms.csv"',
        );

        return response()->make(rtrim($output, "\n"), 200, $headers);
    }
}
