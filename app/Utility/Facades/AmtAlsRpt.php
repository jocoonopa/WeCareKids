<?php

namespace App\Utility\Facades;

class AmtAlsRpt extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() 
    { 
        return 'amt_als_rpt';
    }

    public static function foo()
    {
        return 'bar';
    }
}