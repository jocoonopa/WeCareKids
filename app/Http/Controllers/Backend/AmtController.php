<?php

namespace App\Http\Controllers\Backend;

use AmtCell;
use App\Http\Requests;
use App\Model\Amt;
use App\Model\AmtCategory;
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
        $amts = Amt::paginate(env('PERPAGE_COUNT', 50));

        return view('/backend/amt/index', compact('amts'));
    }

    public function show(Amt $amt)
    {
        $menus = AmtCategory::getMenus();

        return view('/backend/amt/show', compact('amt', 'menus'));
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
