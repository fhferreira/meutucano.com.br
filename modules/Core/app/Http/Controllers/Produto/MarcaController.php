<?php namespace Core\Http\Controllers\Produto;

use App\Http\Controllers\Rest\RestControllerTrait;
use App\Http\Controllers\Controller;
use Core\Models\Produto\Marca;

/**
 * Class MarcaController
 * @package Core\Http\Controllers\Produto
 */
class MarcaController extends Controller
{
    use RestControllerTrait;

    const MODEL = Marca::class;

    /**
     * Lista marcas para a tabela
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableList()
    {
        $list = (self::MODEL)::orderBy('marcas.created_at', 'DESC');

        $list = $this->handleRequest($list);

        return $this->listResponse($list);
    }
}
