<?php namespace App\Http\Controllers\Linha;

use Carbon\Carbon;
use App\Http\Controllers\RestControllerTrait;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Linha;
use App\Models\LinhaAtributo;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

/**
 * Class LinhaController
 * @package App\Http\Controllers\Linha
 */
class LinhaController extends Controller
{
    use RestControllerTrait;

    const MODEL = Linha::class;

    protected $validationRules = [];

    /**
     * Lista linhas para a tabela
     * 
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function tableList() {
        $m = self::MODEL;

        $list = $m::orderBy('linhas.created_at', 'DESC');

        $list = $this->handleRequest($list);

        return $this->listResponse($list);
    }

    /**
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
            $v = \Validator::make(Input::all(), $this->validationRules);

            if ($v->fails()) {
                throw new \Exception("ValidationException");
            }

            $data->fill(Input::except(['atributos']));

            if (Input::get('atributos')) {
                $data->atributos()->delete();
                $data->atributos()->insert(Input::get('atributos'));
            }

            $data->save();
            return $this->showResponse($data);
        } catch(\Exception $ex) {
            $data = ['form_validations' => $v->errors(), 'exception' => $ex->getMessage()];
            return $this->clientErrorResponse($data);
        }
    }
}