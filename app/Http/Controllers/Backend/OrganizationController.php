<?php

namespace App\Http\Controllers\Backend;

use App\Model\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  App\Model\Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        return view('backend/organization/show', compact('organization'));
    }
}
