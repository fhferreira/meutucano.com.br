<?php namespace Magento\Events\Handlers;

use Illuminate\Events\Dispatcher;
use Core\Events\ProductStockUpdated;
use Magento\Jobs\SendStockInfo;

class AddStockToQueue
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
            ProductStockUpdated::class,
            '\Magento\Events\Handlers\AddStockToQueue@onProductStockUpdated'
        );
    }

    /**
     * Handle the event.
     *
     * @param  ProductStockUpdated  $event
     * @return void
     */
    public function onProductStockUpdated(ProductStockUpdated $event)
    {
        \Log::debug('Handler AddStockToQueue acionado!', [$event->productStock]);
        dispatch(with(new SendStockInfo($event->productStock))->onQueue('high'));
    }
}