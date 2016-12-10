<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtCategory extends Model
{
    protected $table = 'amt_categorys';

    public function parent()
    {
        return $this->belonsTo('App\Model\AmtCategory', 'parent_id', 'id'); 
    }

    public function childs()
    {
        return $this->hasMany('App\Model\AmtCategory', 'parent_id', 'id'); 
    }

    public function groups()
    {
        return $this->hasMany('App\Model\AmtDiagGroup', 'category_id', 'id');
    }

    public function listRec()
    {
        $menus = [];

        foreach ($this->childs()->get() as $child) {
            if ($child->id === $this->id) {
                continue;
            }

            $menus[] = [
                'name' => $child->content,
                'child' => true === $child->is_final ? NULL : $child->listRec()
            ];
        }

        return $menus;
    }

    public function scopeFindFinals($query)
    {
        return $query->where('is_final', true);
    }
}
