<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\WckUsageRecordRequest;
use App\Model\Organization;
use App\Model\WckUsageRecord;
use Auth;
use Illuminate\Http\Request;

class WckUsageRecordController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('can:create,' . \App\Model\WckUsageRecord::class)->only('create','store');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Organization $organization)
    {
        return view('backend.wck_usage_record.create', compact('organization'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WckUsageRecordRequest  $request
     * @param  \App\Model\Organization  $organizatoin
     * @return \Illuminate\Http\Response
     */
    public function store(WckUsageRecordRequest $request, Organization $organization)
    {
        try {
            $usage = new WckUsageRecord;
            $usage->user()->associate(Auth::user());
            $usage->organization()->associate($organization);
            $usage->variety = $request->get('variety');
            $usage->brief = $request->get('brief');
            $usage->save();
            
            return redirect("/backend/organization/{$organization->id}/wck_usage_record/create")->with('success', "交易紀錄: {$usage->brief} 新增完成!");
        } catch (\Exception $e) {
            return redirect("/backend/organization/{$organization->id}/wck_usage_record/create")->with('error', "{$e->getMessage()}");
        }
    }
}
