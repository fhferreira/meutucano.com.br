<?php namespace Core\Models;

use App\Models\User\User;
use Venturecraft\Revisionable\RevisionableTrait;

class OrderComment extends \Eloquent
{
    use RevisionableTrait;

    /**
     * @var boolean
     */
    protected $revisionCreationsEnabled = true;

    /**
     * @var array
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'comment',
    ];

    /**
     * @var array
     */
    protected $with = [
        'user',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}