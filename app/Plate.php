<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plate extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ingredients() {
        return $this->belongsToMany(Ingredient::class, 'ingredient_plate');
    }
}
