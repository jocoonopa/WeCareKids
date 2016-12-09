<?php

namespace App\Utility\Services;

class WckService
{
    /**
     * 判斷輸入值是否意義上為空
     * 
     * @param  mixed $some
     * @return boolean      
     */
    public function isEmpty($some)
    {
        if (is_null($some)) {
            return true;
        } 

        if ($some instanceof \Illuminate\Support\Collection) {
            return 0 === $some->count();
        }
        
        if (is_array($some)) {
            return 0 === count($some);
        }

        return false;
    }
}