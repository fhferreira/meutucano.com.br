<?php namespace App\Http\Controllers\Pedido\Rastreio;

use App\Http\Controllers\Rest\RestControllerTrait;
use App\Http\Controllers\Controller;
use App\Models\Pedido\Rastreio;
use App\Models\Pedido\Rastreio\Pi;
use Illuminate\Support\Facades\Input;

/**
 * Class PiController
 * @package App\Http\Controllers\Pedido\Rastreio
 */
class PiController extends Controller
{
    use RestControllerTrait;

    const MODEL = Pi::class;

    protected $validationRules = [];

    /**
     * Retorna uma pi com base no rastreio
     *
     * @param  int $id
     * @return array
     */
    public function show($id)
    {
        $m = Rastreio::class;
        if ($data = $m::find($id)) {
            return $this->showResponse($data->pi);
        }

        return $this->notFoundResponse();
    }

    /**
     * Retorna uma lista de PI's pendentes de ação
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pending()
    {
        $m = self::MODEL;

        $lista = $m::with(['rastreio', 'rastreio.pedido', 'rastreio.pedido.cliente', 'rastreio.pedido.endereco'])
            ->join('pedido_rastreios', 'pedido_rastreios.id', '=', 'pedido_rastreio_pis.rastreio_id')
            ->join('pedidos', 'pedidos.id', '=', 'pedido_rastreios.pedido_id')
            ->join('clientes', 'clientes.id', '=', 'pedidos.cliente_id')
            ->join('cliente_enderecos', 'cliente_enderecos.id', '=', 'pedidos.cliente_endereco_id')
            ->whereNull('pedido_rastreio_pis.status')
            ->orderBy('pedido_rastreio_pis.created_at', 'DESC');

        $lista = $this->handleRequest($lista);

        return $this->listResponse($lista);
    }

    /**
     * Cria novo recurso
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store()
    {
        $m = self::MODEL;
        try {
            $v = \Validator::make(Input::except(['protocolo']), $this->validationRules);

            if($v->fails()) {
                throw new \Exception("ValidationException");
            }
            $data = $m::create(Input::except(['protocolo']));

            if (Input::has('valor_pago')) {
                $data->rastreio->status = 8;
            } else {
                $data->rastreio->status = 7;
            }

            $data->rastreio->save();

            if ((int)$data->acao === 1) {
                if ($protocolo = Input::get('protocolo')) {
                    if ($rastreio = Rastreio::find($data->rastreio_id)) {
                        $rastreio->protocolo = $protocolo;
                        $rastreio->save();
                    }
                }
            }

            return $this->createdResponse($data);
        } catch(\Exception $ex) {
            $data = ['form_validations' => $v->errors(), 'exception' => $ex->getMessage()];

            \Log::error(logMessage($ex, 'Erro ao salvar recurso'));
            return $this->clientErrorResponse($data);
        }
    }

    /**
     * Atualiza um recurso
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id)
    {
        $m = self::MODEL;

        if (!$data = $m::find($id)) {
            return $this->notFoundResponse();
        }

        try {
            $v = \Validator::make(Input::except(['protocolo']), $this->validationRules);

            if ($v->fails()) {
                throw new \Exception("ValidationException");
            }

            $data->fill(Input::except(['protocolo']));
            $data->save();

            if ((int)$data->acao === 1) {
                if ($protocolo = Input::get('protocolo')) {
                    if ($rastreio = Rastreio::find($data->rastreio_id)) {
                        $rastreio->protocolo = $protocolo;
                        $rastreio->save();
                    }
                }
            }

            return $this->showResponse($data);
        } catch(\Exception $ex) {
            \Log::error(logMessage($ex, 'Erro ao atualizar recurso'), ['model' => self::MODEL]);

            $data = ['form_validations' => $v->errors(), 'exception' => $ex->getMessage()];
            return $this->clientErrorResponse($data);
        }
    }
}