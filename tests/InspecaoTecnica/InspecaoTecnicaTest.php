<?php namespace Tests\InspecaoTecnica;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Core\Create\Produto;
use Tests\Core\Create\Pedido;
use Tests\InspecaoTecnica\Create\InspecaoTecnica;

class InspecaoTecnicaTest extends TestCase
{
    use WithoutMiddleware,
        DatabaseTransactions;

    /**
    * Testa se é possível definir uma inspeção como proriodade
    *
    * @return void
    */
    public function test__it_should_be_able_to_change_priority()
    {
        /*$inspecao = InspecaoTecnica::create();

        $this->json('POST', "/api/inspecao_tecnica/priority/{$inspecao->pedido_produtos_id}")
            ->seeJsonStructure([
                'data'
            ])
            ->seeStatusCode(200);

        $inspecao = $inspecao->fresh();
        $this->assertEquals(1, $inspecao->priorizado);*/
    }

    /**
    * Testa se um novo pedido seminovo se associa automaticamente com uma inspeção
    *
    * @return void
    */
    public function test__it_should_be_able_to_attach_inspection_to_new_pedidos()
    {
        /*$produto = Produto::createSeminovo();

        $inspecao = InspecaoTecnica::createWithNoAssociation([
            'produto_sku' => $produto->sku,
            'revisado_at' => date('Y-m-d H:i:s'),
        ]);

        $pedido = Pedido::create(['status' => 1], $produto->sku);

        $inspecao = $inspecao->fresh();

        $this->assertEquals($pedido->produtos()->first()->id, $inspecao->pedido_produtos_id);*/
    }

    /**
    * Testa se um novo pedido cria uma nova inspeção
    *
    * @return void
    */
    public function test__it_should_be_able_to_create_new_inspection_to_new_pedidos()
    {
        /*$produto = Produto::createSeminovo();
        $pedido  = Pedido::create(['status' => 1], $produto->sku);

        $inspecao = InspecaoTecnica::where('pedido_produtos_id', '=',
            $pedido->produtos()->first()->id)
            ->count();

        $this->assertEquals(1, $inspecao);*/
    }

    /**
    * Testa se ao cancelar um pedido, deleta a inspeção sem revisão
    *
    * @return void
    */
    public function test__it_should_delete_inspecao_when_pedido_canceled()
    {
        /*$produto = Produto::createSeminovo();
        $pedido  = Pedido::create(['status' => 1], $produto->sku);
        $pedido->status = 5;
        $pedido->save();

        $inspecao = InspecaoTecnica::where('pedido_produtos_id', '=',
            $pedido->produtos()->first()->id)
            ->count();

        $this->assertEquals(0, $inspecao);*/
    }

    /**
    * Testa se ao cancelar um pedido, desasocia a inspeção já revisada
    *
    * @return void
    */
    public function test__it_should_detach_reviewed_inspecao_when_pedido_canceled()
    {
        /*$produto    = $this->createProdutoSeminovo();
        $pedido     = $this->createOrder(['status' => 1], $produto->sku);
        $inspection = InspecaoTecnica::where('pedido_produtos_id', '=', $pedido->produtos()->first()->id)
                            ->first();

        $inspection->revisado_at = date('Y-m-d H:i:s');
        $inspection->save();

        $pedido->status = 5;
        $pedido->save();

        $inspection = $inspection->fresh();

        $this->assertEquals(null, $inspection->pedido_produtos_id);*/
    }

    /**
     * Testa se quando o produto de um pedido produto for alterado, e a inspecao nao foi revisada, exclui ela
     *
     * @return void
     */
    public function test__it_should_delete_non_reviewed_inspection_when_product_changed()
    {
        /*$product      = Produto::createSeminovo();
        $order        = Pedido::create(['status' => 1], $product->sku);
        $orderProduct = $order->produtos()->first();
        $inspection   = InspecaoTecnica::where('pedido_produtos_id', '=', $orderProduct->id)->whereNull('revisado_at')
                            ->first();

        $orderProduct->produto_sku = (Produto::createSeminovo())->sku;
        $orderProduct->save();

        $inspection = $inspection->fresh();

        $this->assertEquals(null, $inspection);*/
    }

    /**
     * Testa se quando o produto de um pedido produto for alterado, e a inspecao foi revisada, desassocia
     *
     * @return void
     */
    public function test__it_should_detach_reviewed_inspection_when_product_changed()
    {
        /*$product      = Produto::createSeminovo();
        $order        = Pedido::create(['status' => 1], $product->sku);
        $orderProduct = $order->produtos()->first();
        $inspection   = InspecaoTecnica::where('pedido_produtos_id', '=', $orderProduct->id)
                            ->first();

        $inspection->revisado_at = date('Y-m-d H:i:s');
        $inspection->save();

        $orderProduct->produto_sku = (Produto::createSeminovo())->sku;
        $orderProduct->save();

        $inspection = $inspection->fresh();

        $this->assertEquals(null, $inspection->pedido_produtos_id);*/
    }

    /**
     * Testa se quando o produto de um pedido produto for alterado, aloca novas inspeções
     *
     * @return void
     */
    public function test__it_should_attach_inspection_when_product_changed()
    {
        /*$product      = Produto::createSeminovo();
        $order        = Pedido::create(['status' => 1], $product->sku);
        $orderProduct = $order->produtos()->first();

        $orderProduct->produto_sku = (Produto::createSeminovo())->sku;
        $orderProduct->save();

        $inspections = $orderProduct->inspecoes()->get();

        $this->assertEquals($orderProduct->quantidade, $inspections->count());*/
    }

    /**
     * Testa se quando o pedido produto for excluido e tiver uma inspecao revisada, desassocia ela
     *
     * @return void
     */
    public function test__it_should_detach_reviewed_inspection_when_order_product_is_deleted()
    {
        /*$product      = Produto::createSeminovo();
        $order        = Pedido::create(['status' => 1], $product->sku);
        $orderProduct = $order->produtos()->first();
        $inspection   = InspecaoTecnica::where('pedido_produtos_id', '=', $orderProduct->id)
                            ->first();

        $inspection->revisado_at = date('Y-m-d H:i:s');
        $inspection->save();

        $orderProduct->delete();
        $inspection = $inspection->fresh();

        $this->assertEquals(null, $inspection->pedido_produtos_id);*/
    }

    /**
     * Testa se quando o pedido produto for excluido e tiver uma inspecao nao revisada, exclui ela
     *
     * @return void
     */
    public function test__it_should_delete_non_reviewed_inspection_when_order_product_is_deleted()
    {
        /*$product      = Produto::createSeminovo();
        $order        = Pedido::create(['status' => 1], $product->sku);
        $orderProduct = $order->produtos()->first();
        $inspection   = InspecaoTecnica::where('pedido_produtos_id', '=', $orderProduct->id)->whereNull('revisado_at')
                            ->first();

        $orderProduct->delete();
        $inspection = $inspection->fresh();

        $this->assertEquals(null, $inspection);*/
    }
}
