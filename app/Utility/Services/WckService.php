<?php

namespace App\Utility\Services;

use App\Utility\Services\Wck\ViewTrait;
use Auth;

class WckService
{
    use ViewTrait;

    protected $cacheChannel;

    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

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

    public function convertJson2Utf8($value)
    {
        $data = json_decode($value, true);

        if (!is_array($data)) {
            return $value;
        }

        array_walk_recursive($data, function(&$value, $key) {
            if(is_string($value)) {
                $value = urlencode($value);
            }
        });
        
        return urldecode(json_encode($data));
    }

    public function extractValue2JsonUtf8($data)
    {   
        $new = [];
        foreach ($data as $key => $val) {
            $new[urlencode($key)] = urlencode($val);
        }

        return urldecode(json_encode($new));
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getUserChannel()
    {
        if (is_null($this->cacheChannel)) {
            $this->cacheChannel = $this->getUser()->getOwnChannel();
        }

        return $this->cacheChannel;
    }
}