<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\OrganizationRequest;
use App\Model\Organization;
use App\Model\User;
use App\Model\WckUsageRecord;
use Auth;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('can:all,' . \App\Model\Organization::class)->only('index');
        $this->middleware('can:view,organization')->only('show');
        $this->middleware('can:create,' . \App\Model\Organization::class)->only('create', 'store');
        $this->middleware('can:update,organization')->only('edit', 'update');
        $this->middleware('can:delete,organization')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::latest()->get();

        return view('backend/organization/index', compact('organizations'));
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Model\Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        $usages = $organization->usages()->orderBy('id', 'desc')->paginate(env('PERPAGE_COUNT', 50));

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
            $organization->region = $request->get('region');
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
    public function create(Request $request)
    {
        $organization = new Organization;
        $organization->name = old('name');
        $organization->region = old('region');

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
                User::find($request->get('contacter')), 
                Auth::user(), 
                $request->get('name'),
                $request->get('region')
            );

            return redirect('backend/organization')->with('success', "{$organization->name} 新增完成!")->withInput();
        } catch (\Exception $e) {
            return redirect('backend/organization')->with('error', "{$e->getMessage()}");
        }        
    }

    /**
     * Delete the specified resource.
     * 
     * @param  \App\Model\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect('backend/organization')->with('success', "{$organization->name} 刪除完成!");
    }
}
