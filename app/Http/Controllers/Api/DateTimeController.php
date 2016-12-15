<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Child;
use Illuminate\Http\Request;

class DateTimeController extends Controller
{
    public function getYMAge(Request $request)
    {   
        return Child::getYMAge(new \DateTime($request->get('birthday')));
    }
}
