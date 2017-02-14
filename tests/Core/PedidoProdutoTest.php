<?php namespace Tests\Core;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Core\Create\Pedido;
use Tests\Core\Create\Produto;
use Tests\Core\Create\ProductImei;
use Tests\Core\Create\ProductStock;
use Tests\Core\Create\PedidoProduto;

class PedidoProdutoTest extends TestCase
{
    use WithoutMiddleware,
        DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->produto = Produto::create([
            'estoque' => 10
        ]);
    }

    /**
     * If can create pedido produto via api
     * @return void
     */
    public function test__it_should_be_able_to_create_order_product()
    {
        $data = [
            'pedido_id'        => Pedido::create()->id,
            'produto_sku'      => Produto::create()->sku,
            'product_imei_id'  => ProductImei::create()->id,
            'product_stock_id' => ProductStock::create()->id,
            'valor'            => 99.90,
        ];

        $this->json('POST', '/api/pedido-produtos', $data)
            ->seeStatusCode(201);

        $this->seeInDatabase('pedido_produtos', $data);
    }

    /**
     * If can update pedido produto via api
     * @return void
     */
    public function test__it_should_be_able_to_update_order_product()
    {
        $orderProduct = PedidoProduto::create();

        $data = [
            'pedido_id'        => Pedido::create()->id,
            'produto_sku'      => Produto::create()->sku,
            'product_imei_id'  => ProductImei::create()->id,
            'product_stock_id' => ProductStock::create()->id,
            'valor'            => 2.50,
        ];

        $this->json('PUT', "/api/pedido-produtos/{$orderProduct->id}", $data)
            ->seeStatusCode(200);

        $this->seeInDatabase('pedido_produtos', $data);
    }
}
