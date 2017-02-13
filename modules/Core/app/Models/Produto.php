<?php namespace Core\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Venturecraft\Revisionable\RevisionableTrait;
use InspecaoTecnica\Models\InspecaoTecnica;
use Core\Models\Pedido\PedidoProduto;
use Core\Models\Produto\ProductStock;

/**
 * Class Produto
 * @package Core\Models
 */
class Produto extends Model
{
    use RevisionableTrait, Eloquence;

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
        'marca_id',
        'linha_id',
        'titulo',
        'ncm',
        'ean',
        'referencia',
        'valor',
        'unidade',
        'estado',
        'ativo',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'ativo'           => 'string',
        'estado'          => 'string',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'estoque',
    ];

    /**
     * ProductStock
     * @return ProductStock
     */
    public function productStocks()
    {
        return $this->hasMany(ProductStock::class, 'product_sku', 'sku');
    }

    /**
     * ProductStock
     * @return ProductStock
     */
    public function pedidoProdutos()
    {
        return $this->hasMany(PedidoProduto::class, 'produto_sku', 'sku');
    }

    /**
     * InspecaoTecnica
     * @return InspecaoTecnica
     */
    public function inspecoesTecnicas()
    {
        return $this->hasMany(InspecaoTecnica::class, 'produto_sku', 'sku');
    }

    /**
     * Return the sum of included stocks
     * @return int
     */
    public function getStock()
    {
        $stock = 0;

        $productStocks = $this->productStocks()
                ->join('stocks', 'stocks.slug', 'product_stocks.stock_slug')
                ->where('stocks.include', '=', true)
                ->get();

        foreach ($productStocks as $productStock) {
            $stock += $productStock->quantity;
        }

        $reservados = $this->pedidoProdutos()
            ->join('pedidos', 'pedidos.id', 'pedido_produtos.pedido_id')
            ->whereIn('pedidos.status', [0, 1])
            ->count();

        $stock -= $reservados;

        return $stock;
    }

    /**
     * Return calculated estoque
     * @return int quantity in stock
     */
    public function getEstoqueAttribute()
    {
        return $this->getStock();
    }

    /**
     * @var array
     */
    /*protected $with = [
        'linha',
        'marca',
        'atributos',
    ];*/

    /**
     * Linha
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    /*public function linha()
    {
        return $this->belongsTo(Linha::class);
    }*/

    /**
     * Marca
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    /*public function marca()
    {
        return $this->belongsTo(Marca::class);
    }*/

    /**
     * Atributos
     * @return Object
     */
    /*public function atributos()
    {
        return $this
            ->belongsToMany(Atributo::class, 'produto_atributo', 'produto_sku', 'atributo_id')
            ->withPivot('opcao_id', 'valor');
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists)
    {
        if ($parent instanceof Atributo) {
            return new ProdutoAtributoPivot($parent, $attributes, $table, $exists);
        }

        return parent::newPivot($parent, $attributes, $table, $exists);
    }*/
}
