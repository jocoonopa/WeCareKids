<?php

namespace App\Http\Controllers\Backend;

use AmtCell;
use App\Http\Requests;
use App\Model\Amt;
use Auth;
use Illuminate\Http\Request;

class AmtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $amts = Auth::user()->amts;

        return view('/backend/amt/index', compact('amts'));
    }

    public function show(Amt $amt)
    {
        return view('/backend/amt/show', compact('amt'));
    }

    public function map(Amt $amt)
    {
        return view('/backend/amt/map', compact('amt'));
    }

    public function edit(Amt $amt)
    {
        return view('/backend/amt/index', compact('amt'));
    }
}
