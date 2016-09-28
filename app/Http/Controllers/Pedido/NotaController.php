<?php namespace App\Http\Controllers\Pedido;

use App\Http\Controllers\Rest\RestControllerTrait;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Integracao\SkyhubController;
use App\Models\Pedido\Nota;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use NFePHP\Extras\Danfe;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Pedido\Nota\Devolucao;

/**
 * Class NotaController
 * @package App\Http\Controllers\Pedido
 */
class NotaController extends Controller
{
    use RestControllerTrait;

    const MODEL = Nota::class;

    protected $validationRules = [];

    /**
     * Retorna notas faturadas pelo usuário atual
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function notasFaturamento()
    {
        $model = self::MODEL;

        $user = JWTAuth::parseToken()->authenticate()->id;

        $notas = $model::with(['pedido', 'pedido.cliente', 'pedido.rastreios'])
            ->where('usuario_id', $user)
            ->where('updated_at', '>=', date('Y-m-d'))
            ->get();

        return $this->listResponse($notas);
    }

    /**
     * Gera o XML da nota fiscal
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function xml($id, $devolucao)
    {
        $model = self::MODEL;

        if ($devolucao) {
            $nota = Devolucao::find($id);
        } else {
            $nota = $model::find($id);
        }

        if ($nota) {
            $file_path = storage_path('app/public/nota/'. $nota->arquivo);

            if (file_exists($file_path)) {
                return response()->make(file_get_contents($file_path), '200')->header('Content-Type', 'text/xml');
            }
        }

        return $this->notFoundResponse();
    }

    /**
     * Envia um e-mail ao cliente com a nota fiscal
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function email($id)
    {
        $model = self::MODEL;

        if ($nota = $model::find($id)) {
            $email = Input::get('email');
            $dataHora = date('His');

            if (!$nota->pedido->cliente->email) {
                $cliente = $nota->pedido->cliente;
                $cliente->email = $email;
                $cliente->save();
            }

            Mail::send('emails.danfe', [], function($message) use ($id, $dataHora, $email) {
                with(new NotaController())->danfe($id, 'F', storage_path('app/public/' . $dataHora . '.pdf'));

                $message
                    ->attach(storage_path('app/public/' . $dataHora . '.pdf'), ['as' => 'nota.pdf', 'mime' => 'application/pdf'])
                    ->from('vendas@cariocacelulares.com.br', 'Carioca Celulares Online')
                    ->to($email)
                    ->subject('Nota fiscal de compra na Carioca Celulares Online');
            });

            unlink(storage_path('app/public/' . $dataHora . '.pdf'));

            return $this->showResponse([]);
        }

        return $this->notFoundResponse();
    }

    /**
     * Gera o arquivo PDF da DANFe
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function danfe($id, $retorno = 'I', $caminhoArquivo = false)
    {
        $model = self::MODEL;

        if ($nota = $model::find($id)) {
            $file_path = storage_path('app/public/nota/'. $nota->arquivo);

            if (file_exists($file_path)) {
                $xml = file_get_contents($file_path);

                $danfe = new Danfe(
                    $xml,
                    'P',
                    'A4',
                    public_path('assets/img/logocarioca.jpg'),
                    public_path('assets/img/watermark.jpg'),
                    $retorno,
                    ''
                );

                $nomeDanfe = ($caminhoArquivo) ?: substr($nota->arquivo, 0, -4) . '.pdf';

                $danfe->montaDANFE('P', 'A4', 'L');
                $danfe->printDANFE($nomeDanfe, $retorno);
            }
        }

        return $this->notFoundResponse();
    }

    /**
     * Envia os dados de faturamento para a Skyhub
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function faturar($pedido_id)
    {
        return with(new SkyhubController())->orderInvoice($pedido_id);
    }
}