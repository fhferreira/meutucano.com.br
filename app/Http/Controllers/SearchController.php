<?php namespace App\Http\Controllers;

use App\Http\Controllers\RestResponseTrait;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoNota;
use App\Models\PedidoRastreio;
use App\Models\PedidoRastreioPi;
use App\Models\Produto;
use Illuminate\Support\Facades\Input;

/**
 * Class SearchController
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    use RestResponseTrait;

    public function scan() {
        $notas = glob(storage_path('app/public/nota/*.{xml}'), GLOB_BRACE);

        foreach ($notas as $nota) {
            $xml = simplexml_load_file($nota);
            try {
                $nfe = $xml->NFe->infNFe;

                $info = $nfe->infAdic->infCpl;

                if (strpos($info, '359561064445917') !== false) {
                    echo $nota;
                    break;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    /**
     * Busca de pedidos
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search()
    {
        $query = Input::get('search');

        /**
         * Pedidos
         */
        $busca = Pedido::with([
            'nota',
            'cliente',
            'rastreios', 'rastreios.rastreioRef',
            'rastreios.pedido', 'rastreios.pedido.cliente', 'rastreios.pedido.endereco',
            'rastreios.pi', 'rastreios.pi.rastreioRef',
            'rastreios.devolucao', 'rastreios.devolucao.rastreioRef',
            'rastreios.logistica', 'rastreios.logistica.rastreioRef',
        ])
            ->join('pedido_notas', 'pedidos.id', '=', 'pedido_notas.pedido_id')
            ->join('clientes', 'pedidos.cliente_id', '=', 'clientes.id')
            ->leftJoin('pedido_rastreios', 'pedidos.id', '=', 'pedido_rastreios.pedido_id')
            ->leftJoin('pedido_rastreio_pis', 'pedido_rastreios.id', '=', 'pedido_rastreio_pis.rastreio_id')
            ->leftJoin('pedido_rastreio_logisticas', 'pedido_rastreios.id', '=', 'pedido_rastreio_logisticas.rastreio_id')

            ->orWhere('pedidos.id', 'LIKE', '%' . $query . '%')
            ->orWhere('pedidos.codigo_marketplace', 'LIKE', '%' . $query . '%')
            ->orWhere('clientes.nome', 'LIKE', '%' . $query . '%')
            ->orWhere('pedido_rastreios.rastreio', 'LIKE', '%' . $query . '%')
            ->orWhere('pedido_rastreio_pis.codigo_pi', 'LIKE', '%' . $query . '%')
            ->orWhere('pedido_rastreio_logisticas.autorizacao', 'LIKE', '%' . $query . '%')

            ->groupBy('pedidos.id')
            ->orderBy('pedidos.created_at', 'DESC')

            ->get([
                'pedidos.*'
            ]);

        return $this->listResponse($busca);
    }
}
