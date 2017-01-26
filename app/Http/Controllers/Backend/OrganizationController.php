<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\OrganizationRequest;
use App\Model\Organization;
use App\Model\User;
use App\Model\WckUsageRecord;
use Auth;

class OrganizationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('can:view,organization')->only('show');
        $this->middleware('can:create,' . \App\Model\Organization::class)->only('index', 'create', 'store');
        $this->middleware('can:update,organization')->only('edit', 'update');
    }

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::latest()->get();

        return view('backend.organization.index', compact('organizations'));
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Model\Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        $usages = $organization->usages()->latest()->paginate(env('PERPAGE_COUNT', 50));

        return view('backend/organization/show', compact('organization', 'usages'));
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  App\Model\Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization)
    {
        $users = User::findOrgOptions($organization)->get();

        return view('backend/organization/edit', compact('organization', 'users'));
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  OrganizationRequest
     * @param  App\Model\Organization $organization
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(OrganizationRequest $request, Organization $organization)
    {
        try {
            $organization->name = $request->get('name');
            $organization->owner()->associate(User::find($request->get('owner')));
            $organization->contacter()->associate(User::find($request->get('contacter')));
            $organization->save();

            return redirect("backend/organization")->with('success', "{$organization->name} 更新完成!");    
        } catch (\Exception $e) {
            return redirect("backend/organization/{$organization->id}/edit")->with('error', "{$e->getMessage()}");    
        }
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization = new Organization;

        $users = User::findOrgOptions($organization)->get();

        return view('backend/organization/create', compact('organization', 'users'));
    }

    /**
     * Store specified resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationRequest $request)
    {
        try {
            $organization = Organization::_create(
                User::find($request->get('owner')), 
                $contacter, 
                Auth::user(), 
                $request->get('name')
            );

            return redirect('backend/organization')->with('success', "{$organization->name} 新增完成!");
        } catch (\Exception $e) {
            return redirect('backend/organization')->with('error', "{$e->getMessage()}");
        }        
    }
}
