<?php namespace Core\Events;

use Illuminate\Queue\SerializesModels;
use Core\Models\Pedido;

class OrderSent extends \Event
{
    use SerializesModels;

    public $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Pedido $order)
    {
        \Log::debug('Evento OrderSent disparado');
        $this->order = $order;

        \Event::fire(new \Gamification\Events\TarefaRealizada('fature-um-pedido'));
    }
}
