<?php

namespace App\Utility\Facades;

class Alsrpt extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() 
    { 
        return 'als_rpt';
    }

    public static function foo()
    {
        return 'bar';
    }
}