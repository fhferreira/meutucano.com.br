<?php namespace InspecaoTecnica\Events\Handlers;

use Core\Events\OrderProductDeleting;
use Core\Events\OrderProductProductChanged;
use Core\Events\OrderProductQtyDecreased;
use Core\Models\Pedido\PedidoProduto;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;
use InspecaoTecnica\Http\Controllers\InspecaoTecnicaController;

class DetachInspecaoTecnica
{
    /**
     * Set events that this will listen
     *
     * @param  Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            OrderProductQtyDecreased::class,
            '\InspecaoTecnica\Events\Handlers\DetachInspecaoTecnica@onOrderProductQtyDecreased'
        );

        $events->listen(
            OrderProductProductChanged::class,
            '\InspecaoTecnica\Events\Handlers\DetachInspecaoTecnica@onOrderProductProductChanged'
        );

        $events->listen(
            OrderProductDeleting::class,
            '\InspecaoTecnica\Events\Handlers\DetachInspecaoTecnica@onOrderProductDeleting'
        );
    }

    /**
     * Handle the event.
     *
     * @param  OrderProductQtyDecreased  $event
     * @return void
     */
    public function onOrderProductQtyDecreased(OrderProductQtyDecreased $event)
    {
        $orderProduct = $event->orderProduct;
        $qty          = $event->qty;
        $orderProduct = $orderProduct->fresh();

        Log::debug('Handler DetachInspecaoTecnica/OrderProductQtyDecreased acionado.', [$event]);
        for ($i=0; $i < $qty; $i++) {
            with(new InspecaoTecnicaController())->detachInspecao($orderProduct);
        }
    }

    /**
     * Handle the event.
     *
     * @param  OrderProductProductChanged  $event
     * @return void
     */
    public function onOrderProductProductChanged(OrderProductProductChanged $event)
    {
        $orderProduct = $event->orderProduct;
        $orderProduct = $orderProduct->fresh();

        Log::debug('Handler DetachInspecaoTecnica/OrderProductProductChanged acionado.', [$event]);
        $this->detachInspecao($orderProduct);
    }

    /**
     * Handle the event.
     *
     * @param  OrderProductDeleting  $event
     * @return void
     */
    public function onOrderProductDeleting(OrderProductDeleting $event)
    {
        $orderProduct = $event->orderProduct;
        $orderProduct = $orderProduct->fresh();

        Log::debug('Handler DetachInspecaoTecnica/OrderProductDeleting acionado.', [$event]);
        $this->detachInspecao($orderProduct, true);
    }

    /**
     * Relaciona uma inspecao tecnica com o pedido produto
     *
     * @param  PedidoProduto $orderProduct
     * @return void
     */
    public function detachInspecao(PedidoProduto $orderProduct, $onlyReviewed = false)
    {
        $product = $orderProduct->produto;

        for ($i=0; $i < $orderProduct->quantidade; $i++) {
            Log::debug('Tentando desrelacionar pedido produto e inspecao tecnica.');
            with(new InspecaoTecnicaController())->detachInspecao($orderProduct, $onlyReviewed);
        }
    }
}