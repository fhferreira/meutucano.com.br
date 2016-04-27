<?php

use App\Models\Cliente;
use App\Models\ClienteEndereco;
use App\Models\Pedido;
use App\Models\PedidoImposto;
use App\Models\PedidoNota;
use App\Models\PedidoProduto;
use App\Models\PedidoRastreio;
use App\Models\Produto;

class TestSeeder extends \Illuminate\Database\Seeder {

    /**
     * Run seeder
     */
    public function run()
    {
        foreach(range(1, 10) as $pedido_id) {
            $cliente  = factory(Cliente::class)->create();
            $endereco = factory(ClienteEndereco::class)->make();
            $cliente->enderecos()->save($endereco);

            factory(Pedido::class)->create([
                'id'                  => $pedido_id,
                'cliente_id'          => $cliente->id,
                'cliente_endereco_id' => $endereco->id,
            ]);

            factory(PedidoNota::class)->create([
                'pedido_id'  => $pedido_id,
                'usuario_id' => 1,
            ]);

            factory(PedidoRastreio::class)->create([
                'pedido_id' => $pedido_id,
            ]);

            factory(PedidoImposto::class)->create([
                'pedido_id' => $pedido_id,
            ]);

            $produto = factory(Produto::class)->create();

            factory(PedidoProduto::class)->create([
                'pedido_id'   => $pedido_id,
                'produto_sku' => $produto->sku
            ]);
        }
    }
}