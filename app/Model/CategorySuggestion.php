<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CategorySuggestion extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_better' => 'boolean',
    ];
}
