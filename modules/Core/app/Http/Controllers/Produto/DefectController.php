<?php namespace Core\Http\Controllers\Produto;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Rest\RestControllerTrait;
use App\Http\Controllers\Controller;
use Core\Models\Produto\Defect;
use Core\Models\Produto\ProductImei;
use Core\Transformers\ProductDefectTransformer;
use Core\Http\Requests\DefectRequest as Request;

/**
 * Class DefectController
 * @package Core\Http\Controllers\Produto
 */
class DefectController extends Controller
{
    use RestControllerTrait;

    const MODEL = Defect::class;

    /**
     * Lista para a tabela
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableList()
    {
        $list = (self::MODEL)
            ::join('product_imeis', 'product_imeis.id', 'product_defects.product_imei_id')
            ->join('produtos', 'produtos.sku', 'product_defects.product_sku')
            ->with([
                'productImei',
                'product',
            ])
            ->orderBy('created_at', 'DESC');

        $list = $this->handleRequest($list);

        return $this->listResponse(ProductDefectTransformer::tableList($list));
    }

    /**
     * Create a new resource
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        try {
            $data = Input::except('imei');

            $imei        = Input::get('imei');
            $productImei = ProductImei::where('imei', '=', $imei)->first();

            if (!$productImei) {
                return $this->validationFailResponse([
                    "O imei {$imei} não está registrado."
                ]);
            }

            $defect = (self::MODEL)::create(array_merge(
                $data,
                [
                    'product_imei_id' => $productImei->id,
                    'product_sku'     => $productImei->productStock->product_sku,
                ]
            ));

            return $this->createdResponse($defect);
        } catch (\Exception $exception) {
            \Log::error(logMessage($exception, 'Erro ao salvar recurso'), ['model' => self::MODEL]);

            return $this->clientErrorResponse([
                'exception' => '[' . $exception->getLine() . '] ' . $exception->getMessage()
            ]);
        }
    }

    /**
     * Update a resource
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id)
    {
        try {
            $data = (self::MODEL)::findOrFail($id);
            $data->fill(Input::all());
            $data->save();

            return $this->showResponse($data);
        } catch (\Exception $exception) {
            \Log::error(logMessage($exception, 'Erro ao atualizar recurso'), ['model' => self::MODEL]);

            return $this->clientErrorResponse([
                'exception' => '[' . $exception->getLine() . '] ' . $exception->getMessage()
            ]);
        }
    }

    /**
     * Returns a unique resource
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id)
    {
        try {
            $defect = (self::MODEL)
                ::with('productImei')
                ->findOrFail($id);

            return $this->showResponse($defect);
        } catch (\Exception $exception) {
            \Log::error(logMessage($exception, 'Erro ao obter recurso'), ['model' => self::MODEL]);

            return $this->clientErrorResponse([
                'exception' => '[' . $exception->getLine() . '] ' . $exception->getMessage()
            ]);
        }
    }
}
