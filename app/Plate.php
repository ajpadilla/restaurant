<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plate extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsToMany
     */
    public function ingredients() {
        return $this->belongsToMany(Ingredient::class, 'ingredient_plate');
    }
}
