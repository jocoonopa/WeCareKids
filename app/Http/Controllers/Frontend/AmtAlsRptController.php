<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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
        $this->middleware('view.rpt')->only('show');
    }

    /**
     * 顯示課程內容
     *
     * @param  $courseId
     * @return \Illuminate\Http\Response
     */
    public function course($courseId)
    {
        return view("frontend/amt_als_rpt/course/{$courseId}", ['id' => $courseId]);
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

            return view('frontend/amt_als_rpt/show', $data);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            throw $e;
        }   
    }
}
