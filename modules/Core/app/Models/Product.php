<?php namespace Core\Models;

use Mercadolivre\Models\Ad;
use Venturecraft\Revisionable\RevisionableTrait;

class Product extends \Eloquent
{
    use RevisionableTrait;

    /**
     * @var boolean
     */
    protected $revisionCreationsEnabled = true;

    /**
     * @var string
     */
    protected $primaryKey = 'sku';

    /**
     * @var array
     */
    protected $fillable = [
        'sku',
        'brand_id',
        'line_id',
        'title',
        'ean',
        'ncm',
        'price',
        'cost',
        'condition',
        'warranty'
    ];

    /**
     * @var array
     */
    protected $appends = [
        // 'stock',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function depotProducts()
    {
        return $this->hasMany(DepotProduct::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'product_sku');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entryProducts()
    {
        return $this->hasMany(EntryProduct::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mercadolivreAds()
    {
        return $this->hasMany(Ad::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Return count of reserved stock from product
     *
     * @return int
     */
    public function reservedStock()
    {
        return $this->orderProducts()
            ->selectRaw('order_products.product_sku, count(*) as count')
            ->join('orders', 'orders.id', 'order_products.order_id')
            ->whereIn('orders.status', [Order::STATUS_PENDING, Order::STATUS_PAID])
            ->groupBy('order_products.product_sku');
    }

    /**
     * Return the sum of included stocks
     *
     * @return int
     */
    public function getStock()
    {
        $stock = $this->depotProducts()
                ->join('depots', 'depots.slug', 'depot_products.depot_slug')
                ->where('depots.include', '=', true)
                ->sum('quantity');

        $reserved = $this->stock_reserved;

        return ($stock - $reserved);
    }

    /**
     * Return calculated estoque
     * @return int quantity in stock
     */
    public function getStockAttribute()
    {
        return $this->getStock();
    }
}