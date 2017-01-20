<?php

namespace App\Http\Controllers\Backend;

use AmtAlsRpt as AAR;
use App\Model\AmtAlsRpt;
use App\Utility\Controllers\AmtAlsRptFetch;
use DB;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

class AmtAlsRptController extends Controller
{
    use AmtAlsRptFetch;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('view.rpt')->only('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = AmtAlsRpt::latest()->paginate(env('PERPAGE_COUNT', 50));

        return view('/backend/amt_als_rpt/index', compact('reports'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\AmtAlsRpt $report
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(AmtAlsRpt $report, Request $request)
    {
        DB::beginTransaction();
        try {
            /*
            |--------------------------------------------------------------------------
            | 连结 AlsRptIbCxt 
            |--------------------------------------------------------------------------
            |
            */
            $this->bindCxtIfNeedTo($report, $request);

            $data = $this->fetchData($report);

            DB::commit();

            return view('backend/amt_als_rpt/show', $data);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect('/backend/amt_als_rpt')->with('error', "{$e->getMessage()}");
        }   
    }
}
