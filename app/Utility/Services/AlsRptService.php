<?php

namespace App\Utility\Services;

class AlsRptService
{
    protected $repo;

    public function __construct(\App\Utility\Repositorys\AlsRptRepo $repo)
    {
        $this->repo = $repo;
    }

    public function create()
    {
        return __METHOD__;
    }
}