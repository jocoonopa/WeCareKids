<?php

namespace App\Http\Controllers\Backend;

use AmtCell;
use App\Http\Requests;
use App\Model\Amt;
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
        $output = AmtCell::setStr($request->get('command'))->convertToStatment();

        $diags = AmtCell::getDiags();

        return view('/backend/amt/index', compact('output', 'diags'));
    }

    public function edit(Amt $amt)
    {
        return view('/backend/amt/index', compact('amt'));
    }
}
