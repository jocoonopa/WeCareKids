<?php

namespace App\Utility\Facades;

class AmtCell extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() 
    { 
        return 'amt_cell';
    }
}