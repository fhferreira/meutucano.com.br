<?php

// Extensão do chrome para o shopsystem
Route::get('pedidos/shopsystem/{taxvat}', 'Core\Http\Controllers\Pedido\PedidoController@shopsystem');

Route::group(['middleware' => ['sentry', 'jwt.auth'], 'prefix' => 'api', 'namespace' => 'Core\Http\Controllers'], function() {
    /**
     * Notas
     */
    Route::group(['prefix' => 'notas', 'namespace' => 'Pedido'], function() {
        Route::get('xml/{id}/{devolucao}', 'NotaController@xml');
        Route::get('danfe/{id}/{retorno?}', 'NotaController@danfe');
        Route::post('email/{id}', 'NotaController@email');
    });

    /**
     * Pedido Produto
     */
    Route::resource('pedido-produto', 'Pedido\PedidoProdutoController');

    /**
     * Notas
     */
    Route::resource('notas', 'Pedido\NotaController', ['except' => ['create', 'edit']]);

    /**
     * Comentarios
     */
    Route::get('comentarios/{pedido_id}', 'Pedido\ComentarioController@commentsFromOrder');
    Route::resource('comentarios', 'Pedido\ComentarioController', ['except' => ['create', 'edit']]);

    /**
     * Faturamento
     */
    Route::group(['middleware' => ['role:admin|faturamento']], function() {
        Route::group(['prefix' => 'notas', 'namespace' => 'Pedido'], function() {
            Route::get('faturamento', 'NotaController@notasFaturamento');
            Route::get('faturar/{pedido_id}', 'NotaController@faturar');
        });

        /**
         * Código de rastreio
         */
        Route::get('codigos/gerar/{servico}', 'Pedido\FaturamentoCodigoController@generateCode');
    });

    /**
     * Pedidos
     */
    Route::group(['prefix' => 'pedidos', 'namespace' => 'Pedido'], function() {
        Route::get('cidades/{uf}', 'PedidoController@cidades');
        Route::get('total-orders-status', 'PedidoController@totalOrdersByStatus');
        Route::get('total-orders-date', 'PedidoController@totalOrdersByDate');
        Route::get('total-orders/{mes?}/{ano?}', 'PedidoController@totalOrders');
        Route::get('cancelamento/{id}', 'PedidoController@imagemCancelamento');
        Route::get('list', 'PedidoController@tableList');
        Route::get('faturamento', 'PedidoController@faturamento');
        Route::get('faturar/{pedido_id}', 'PedidoController@faturar');

        Route::post('status/{pedido_id}', 'PedidoController@alterarStatus');

        Route::put('prioridade/{pedido_id}', 'PedidoController@prioridade');
        Route::put('segurar/{pedido_id}', 'PedidoController@segurar');
    });
    Route::resource('pedidos', 'Pedido\PedidoController', ['except' => ['create', 'edit']]);

    /**
     * Produtos
     */
    Route::group(['prefix' => 'produtos', 'namespace' => 'Produto'], function() {
        Route::get('generate-sku/{old_sku?}', 'ProdutoController@gerenateSku');
        Route::get('check-sku/{sku}', 'ProdutoController@checkSku');
        Route::get('list', 'ProdutoController@tableList');
        Route::get('search/{term}', 'ProdutoController@search');
    });
    Route::resource('produtos', 'Produto\ProdutoController');

    /**
     * Marcas
     */
    Route::get('marcas/list', 'Produto\MarcaController@tableList');
    Route::resource('marcas', 'Produto\MarcaController');

    /**
     * Linhas
     */
    Route::get('linhas/list', 'Produto\LinhaController@tableList');
    Route::resource('linhas', 'Produto\LinhaController');

    /**
     * Atributos
     */
    Route::get('atributos/linha/{linha_id}', 'Produto\Linha\AtributoController@fromLinha');

    /**
     * Clientes
     */
    Route::group(['prefix' => 'clientes', 'namespace' => 'Cliente'], function() {
        Route::get('detail/{cliente_id}', 'ClienteController@detail');
        Route::get('list', 'ClienteController@tableList');
        Route::put('email/{cliente_id}', 'ClienteController@changeEmail');
    });
    Route::resource('clientes', 'Cliente\ClienteController', ['except' => ['create', 'edit', 'store']]);

    /**
     * Endereço
     */
    Route::resource('enderecos', 'Cliente\EnderecoController', ['except' => ['create', 'edit']]);

    /**
     * Relatórios
     */
    Route::group(['middleware' => ['role:admin'], 'namespace' => 'Relatorio', 'prefix' => 'relatorios'], function() {
        // ICMS
        Route::get('icms', 'ICMSController@icms');

        // Pedidos genérico
        Route::post('pedido', 'PedidoController@run');
        Route::get('pedido/{return_type}', 'PedidoController@run');

        // Produtos genérico
        Route::post('produto', 'ProdutoController@run');
        Route::get('produto/{return_type}', 'ProdutoController@run');
    });

    /**
     * Partials
     */
    Route::group(['namespace' => 'Partials'], function() {
        Route::post('upload', [
            'middleware' => ['role:admin|gestor|atendimento|faturamento'],
            'uses' => 'UploadController@upload'
        ]);

        Route::get('search', 'SearchController@search');
    });
});