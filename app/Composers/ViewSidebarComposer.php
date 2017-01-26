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

