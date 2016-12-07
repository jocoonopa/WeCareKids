<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Model\AmtReplicaDiag;
use Illuminate\Http\Request;
use AmtCell;

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

        $diags = AmtCell::getReplicaDiags();

        return view('/backend/amt/index', compact('output', 'diags'));
    }
}
