<?php

namespace App\Http\Controllers\Backend;

use App\Model\AmtCategory;
use App\Model\AmtReplica;
use App\Model\Course;
use Illuminate\Http\Request;

class RecommendCourseController extends Controller
{
    public function index()
    {
        $replicas = AmtReplica::latest()->take(20);
        
        return view('backend/recommend_course/index', [
            'replicas' => $replicas,
            'statCategorys' => AmtCategory::findIsStat()->get(),
            'courses' => Course::all()
        ]);
    }
}
