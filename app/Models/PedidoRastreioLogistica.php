<?php namespace App\Models;

use Carbon\Carbon;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class PedidoRastreioLogistica
 * @package App\Models
 */
class PedidoRastreioLogistica extends \Eloquent
{
    use RevisionableTrait;

    /**
     * @var boolean
     */
    protected $revisionCreationsEnabled = true;

    /**
     * @var string
     */
    protected $primaryKey = 'rastreio_id';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['*'];

    /**
     * @var array
     */
    protected $appends = [
        'data_postagem_readable',
        'created_at_readable',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'motivo' => 'string',
        'acao'   => 'string',
    ];

    /**
     * Rastreio
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rastreio()
    {
        return $this->belongsTo(PedidoRastreio::class);
    }


    /**
     * Rastreio Ref
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rastreioRef()
    {
        return $this->hasOne(PedidoRastreio::class, 'rastreio_ref_id');
    }

    /**
     * Return readable Data Postagem
     *
     * @return string
     */
    protected function getDataPostagemReadableAttribute() {
        return ($this->data_postagem) ? Carbon::createFromFormat('Y-m-d', $this->data_postagem)->format('d/m/Y') : null;
    }

    /**
     * Return readable created_at
     *
     * @return string
     */
    protected function getCreatedAtReadableAttribute() {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('d/m/Y');
    }
}