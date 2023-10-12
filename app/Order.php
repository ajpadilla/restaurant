<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'quantity',
        'plate_id',
        'status'
    ];

    /**
     * @return BelongsTo
     */
    public function plate() {
        return $this->belongsTo(Plate::class);
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeOfStatus($query, $value)
    {
        return !$value ? $query : $query->where('orders.status', $value);
    }
}
