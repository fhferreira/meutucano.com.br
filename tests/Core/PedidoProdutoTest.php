<?php namespace Tests\Core;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PedidoProdutoTest extends TestCase
{
  use WithoutMiddleware,
    DatabaseTransactions,
    CreatePedido;

  public function setUp()
  {
    parent::setUp();

    $this->produto = $this->createProduto([
      'estoque' => 10
    ]);
  }

  /**
   * Testa se abaixa o estoque quando recebe um pedido novo
   *
   * @return void
   */
  public function test__it_should_substract_stock_when_order_created()
  {
    $this->createOrder(['status' => 0], $this->produto->sku); // Pendente
    $this->createOrder(['status' => 1], $this->produto->sku); // Pago
    $this->createOrder(['status' => 2], $this->produto->sku); // Enviado
    $this->createOrder(['status' => 3], $this->produto->sku); // Entregue

    $this->produto = $this->produto->fresh();

    $this->assertEquals(6, $this->produto->estoque);
  }

  /**
   * Testa se um pedido novo cancelado não abaixa o estoque
   *
   * @return void
   */
  public function test__it_should_not_substract_stock_when_order_created_is_canceled()
  {
    $this->createOrder(['status' => 5], $this->produto->sku);

    $this->produto = $this->produto->fresh();

    $this->assertEquals(10, $this->produto->estoque);
  }

  /**
   * Testa se aumenta o estoque quando um pedido passa para cancelado
   *
   * @return void
   */
  public function test__it_should_sum_stock_when_order_canceled()
  {
    $pedido = $this->createOrder([], $this->produto->sku);

    $pedido->status = 5;
    $pedido->save();

    $this->produto = $this->produto->fresh();
    $this->assertEquals(10, $this->produto->estoque);
  }
}