<?php namespace Tests\Rastreio;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Core\Create\Pedido;
use Tests\Rastreio\Create\Rastreio;
use Tests\Rastreio\Create\Logistica;

class LogisticaTest extends TestCase
{
    use WithoutMiddleware,
        DatabaseTransactions;

    /**
    * Testa se é possível exibir a logística a partir do rastreio
    *
    * @return void
    */
    public function test__it_should_be_able_to_show_the_logistica_from_rastreio()
    {
        $rastreio = Rastreio::create();

        Logistica::create([
            'rastreio_id' => $rastreio->id
        ]);

        $response = $this->json('GET', "/api/logisticas/{$rastreio->id}")
            ->seeJsonStructure(['data'])
            ->seeStatusCode(200);
    }

    /**
    * Testa se é possível criar uma logistica
    *
    * @return void
    */
    public function test__it_should_be_able_to_create_logistica()
    {
        $rastreio = Rastreio::create();

        $this->json('POST', "/api/logisticas", [
            'autorizacao' => '45456464564',
            'motivo'      => 1,
            'rastreio_id' => $rastreio->id,
        ])->seeStatusCode(201);

        $this->seeInDatabase('pedido_rastreio_logisticas', [
            'motivo'      => 1,
            'rastreio_id' => $rastreio->id,
        ]);
    }

    /**
    * Testa se o status do rastreio fica devolvido quando a logistica é criada
    *
    * @return void
    */
    public function test__it_should_change_rastreio_status_when_logistica_is_created()
    {
        $rastreio = Rastreio::create();

        $this->json('POST', '/api/logisticas', [
            'autorizacao' => '45456464564',
            'motivo'      => 1,
            'acao'        => 1,
            'rastreio_id' => $rastreio->id,
        ])->seeStatusCode(201);

        $rastreio = $rastreio->fresh();

        $this->assertEquals(5, $rastreio->status);
    }

    /**
    * Testa se o protocolo está sendo adicionado ao pedido quando feito logistica
    *
    * @return void
    */
    public function test__it_should_add_protocolo_to_pedido_when_logistica_is_create()
    {
        $pedido = Pedido::create();

        $rastreio = Rastreio::create([
            'pedido_id' => $pedido->id
        ]);

        $this->json('POST', "/api/logisticas", [
            'motivo'      => 1,
            'acao'        => 1,
            'rastreio_id' => $rastreio->id,
            'protocolo'   => '123456',
            'autorizacao' => '45456464564',
        ])->seeStatusCode(201);

        $pedido = $pedido->fresh();

        $this->assertEquals('123456', $pedido->protocolo);
    }
}
