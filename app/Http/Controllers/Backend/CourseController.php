<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function show($id)
    {
        return view('backend/courses/show', compact('id'));
    }
}
