<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtCategory extends Model
{
    const RECURSIVE_CURRENT_KEY = 'content';
    const RECURSIVE_CHILD_KEY = 'child';

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

    /**
     * 取得分類樹
     * 
     * @return array
     */
    public static function getMenus()
    {
        $menus = [];
        $categorys = static::findRoots()->get();

        $categorys->each(function ($category) use (&$menus) {
            static::pushMenus($menus, $category);
        });

        return $menus;
    }

    protected static function pushMenus(array &$menus, AmtCategory $category)
    {
        $menus[] = [
            static::RECURSIVE_CURRENT_KEY => $category,
            static::RECURSIVE_CHILD_KEY => true === $category->is_final ? NULL : $category->_listRec()
        ];
    }

    protected function _listRec()
    {
        $menus = [];

        foreach ($this->childs()->get() as $category) {
            if ($category->id === $this->id) {
                continue;
            }

            static::pushMenus($menus, $category);
        }

        return $menus;
    }

    public function scopeFindFinals($query)
    {
        return $query->where('is_final', true);
    }

    public function scopeFindRoots($query)
    {
        return $query->where('step', 0);
    }
}
