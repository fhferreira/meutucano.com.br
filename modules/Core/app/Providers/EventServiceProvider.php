<?php namespace Core\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Core\Events\Handlers\SetRefund;
use Core\Events\Handlers\UpdateStock;
use Core\Events\Handlers\DeleteProductImei;
use Core\Events\Handlers\RestoreProductImei;
use Core\Events\Handlers\ConvertEntryImeis;
use Core\Events\Handlers\SetProductCost;
use Core\Events\Handlers\SetProductImeiCost;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    protected $subscribe = [
        UpdateStock::class,
        SetRefund::class,
        DeleteProductImei::class,
        RestoreProductImei::class,
        ConvertEntryImeis::class,
        SetProductCost::class,
        SetProductImeiCost::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
