<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Model\AmtCategory;
use App\Model\AmtReplica;
use App\Model\Course;
use Illuminate\Http\Request;

class RecommendCourseController extends Controller
{
    public function index()
    {
        $replicas = AmtReplica::latest()->get();
        
        return view('backend/recommend_course/index', [
            'statCategorys' => AmtCategory::findIsStat()->get(),
            'courses' => Course::all()
        ]);
    }
}
