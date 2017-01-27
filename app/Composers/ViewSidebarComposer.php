<?php

namespace App\Composers;

use Auth;
use Illuminate\View\View;

class ViewSidebarComposer
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('counts', $this->fetchCounts());
        $view->with('menus', $this->getMenus());
    }

    protected function getMenus()
    {
        if ($this->user->isSuper()) {
            return $this->getSuperMenus();
        }

        if ($this->user->isOwner()) {
            return $this->getOwnerMenus();
        }

        return $this->getTutorMenus();
    }

    protected function getSuperMenus()
    {
        return [
            [
                'name' => '加盟商管理',
                'url' => '/backend/organization',
            ],
            [
                'name' => '教师控管',
                'url' => '/backend/user',
            ],
            [
                'name' => '孩童管理',
                'url' => '/backend/child',
            ],
            [
                'name' => '测评管理',
                'url' => '/backend/amt_replica',
            ],
        ];
    }

    protected function getTutorMenus()
    {
        return [
            [
                'name' => '教师列表',
                'url' => '/backend/user',
            ],
            [
                'name' => '孩童管理',
                'url' => '/backend/child',
            ],            
            [
                'name' => '测评管理',
                'url' => '/backend/amt_replica',
            ],
            [
                'name' => '问卷管理',
                'url' => '/backend/analysis/r/i/cxt',
            ],
        ];
    }

    protected function getOwnerMenus()
    {
        return [
            [
                'name' => '金流显示',
                'url' => "/backend/organization/{$this->user->organization->id}",
            ],
            [
                'name' => '教师控管',
                'url' => '/backend/user',
            ],
            [
                'name' => '孩童管理',
                'url' => '/backend/child',
            ],
        ];
    }

    protected function fetchCounts()
    {
        if ($this->user->isSuper()) {
            return $this->fetchSuperCounts();
        }

        if ($this->user->isOwner()) {
            return $this->fetchOwnerCounts();
        }

        return $this->fetchTutorCounts();
    }

    protected function fetchTutorCounts()
    {
        return [
            'rpt' => $this->user->reports()->count(),
            'cxt' => $this->user->reports()->whereNull('cxt_id')->count(),
            'child' => $this->user->childs()->count(),
            'points' => NULL,
        ];
    }

    protected function fetchOwnerCounts()
    {
        return [
            'rpt' => $this->user->organization->reports()->count(),
            'cxt' => $this->user->organization->reports()->whereNull('cxt_id')->count(),
            'child' => $this->user->organization->childs()->count(),
            'points' => $this->user->organization->points,
        ];
    }

    protected function fetchSuperCounts()
    {
        return [
            'rpt' => \App\Model\AmtAlsRpt::count(),
            'cxt' => \App\Model\AmtAlsRpt::whereNull('cxt_id')->count(),
            'child' => \App\Model\Child::count(),
            'points' => NULL,
        ];
    }
}

