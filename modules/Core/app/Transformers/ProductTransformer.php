<?php namespace Core\Transformers;

use Core\Transformers\Parsers\ProductParser;

/**
 * Class ProductTransformer
 * @package Core\Transformers
 */
class ProductTransformer
{
    /**
     * @param  object $products
     * @return array
     */
    public static function search($products)
    {
        $transformed = [];

        foreach ($products as $product) {
            $transformed[] = [
                'sku'                => $product['sku'],
                'titulo'             => $product['titulo'],
                'estoque'            => $product['estoque'],
                'estado'             => $product['estado'],
                'estado_description' => ProductParser::getEstadoDescription($product['estado']),
                'ean'                => $product['ean'],
                'referencia'         => $product['referencia'],
            ];
        }

        return $transformed;
    }

    /**
     * @param  object $products
     * @return array
     */
    public static function list($products)
    {
        $pagination  = $products->toArray();
        $transformed = [];

        foreach ($products as $product) {
            $transformed[] = [
                'sku'                => $product['sku'],
                'titulo'             => $product['titulo'],
                'linha'              => $product['linha'],
                'marca'              => $product['marca'],
                'estoque'            => $product['estoque'],
                'estado'             => $product['estado'],
                'estado_description' => ProductParser::getEstadoDescription($product['estado']),
                'attachedProducts'   => $product['attachedProducts'],
            ];
        }

        $pagination['data'] = $transformed;

        return $pagination;
    }

    /**
     * @param  object $product
     * @return array
     */
    public static function show($product)
    {
        return [
            'sku'              => $product->sku,
            'unidade'          => $product->unidade,
            'ativo'            => $product->ativo,
            'estado'           => $product->estado,
            'sku'              => $product->sku,
            'ncm'              => $product->ncm,
            'titulo'           => $product->titulo,
            'estoque'          => $product->getStock(),
            'revisoes'         => $product->revisoes,
            'attachedProducts' => $product->attachedProducts,
        ];
    }
}
