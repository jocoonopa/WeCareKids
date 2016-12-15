<?php

namespace App\Model;

use App\Model\AmtCategory;
use Illuminate\Database\Eloquent\Model;

class AmtCommentDesc extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo('App\Model\AmtCategory', 'category_id', 'id');
    }

    public function scopeFindDescByLevel($query, $level)
    {
        return $query->where('level', 0 === (int) $level ? 1 : $level);
    }
}
