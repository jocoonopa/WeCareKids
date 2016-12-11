<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtCategory extends Model
{
    const RECURSIVE_CURRENT_KEY = 'content';
    const RECURSIVE_CHILD_KEY = 'child';
    const STEP_STAT_ID = 2;

    protected $table = 'amt_categorys';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_final' => 'boolean',
    ];

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
     * 找出所有屬於該 AmtCategory 且為最末的 AmtCategory(含自己)
     * 
     * @param  array  &$finals
     * @return \App\Model\AmtCategory $this
     */
    public function findFinals(array &$finals)
    {
        if ($this->isFinal()) {
            $finals[] = $this;

            return $this;
        }

        $this->childs()->each(function ($category) use (&$finals) {            
            return $category->findFinals($finals);
        });

        return $this;
    }

    /**
     * 判斷該 AmtCategory 是否為最末
     * 
     * @return boolean
     */
    public function isFinal()
    {
        return true === $this->is_final;
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
