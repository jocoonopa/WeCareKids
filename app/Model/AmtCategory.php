<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtCategory extends Model
{
    const RECURSIVE_CURRENT_KEY = 'content';
    const RECURSIVE_CHILD_KEY = 'child';
    const STEP_ROOT_ID = 0;
    const STEP_STAT_ID = 2;

    const ID_FEEL_INTEGRATE = 4;
    const ID_ROUGH_ACTION = 5;

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

    public function commentDescs()
    {
        return $this->hasMany('App\Model\AmtCommentDesc', 'category_id', 'id');
    }

    public function scopeFindIsFinal($query)
    {
        return $query->where('is_final', true);
    }

    public function scopeFindIsRoot($query)
    {
        return $query->where('step', static::STEP_ROOT_ID);
    }

    public function scopeFindIsStat($query)
    {
        return $query->where('step', static::STEP_STAT_ID);
    }

    /**
     * 找出所有屬於該 AmtCategory 且為最末的 AmtCategory(含自己)
     * 
     * @param  array  &$finals
     * @return \App\Model\AmtCategory $this
     */
    public function findFinals(array &$finals, $isInLoop = false)
    {   
        if ($this->isFinal() && false === $isInLoop) {
            $finals[] = $this;

            return $this;
        }

        return $this->childs()->each(function ($category) use (&$finals) {                
            if ($category->isFinal()) {
                $finals[] = $category;
            }      

            return $category->findFinals($finals, true);
        });
    }

    public function findPosterity(array &$posteritys)
    {
        foreach ($this->childs as $child) {
            $posteritys[] = $child;

            if (false === $child->isFinal()) {
                $child->findPosterity($posteritys);
            }
        }

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

    public function isStat()
    {
        return static::STEP_STAT_ID === $this->step;
    }

    /**
     * 取得分類樹
     * 
     * @return array
     */
    public static function getMenus()
    {
        $menus = [];
        $categorys = static::findIsRoot()->get();

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
}
