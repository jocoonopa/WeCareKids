@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>推薦課程</h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        @foreach ($statCategorys as $category)
                            <th>{{ $category->content }}</th>
                        @endforeach

                        @foreach ($courses as $course)
                            <th>{{ $course->name }}</th>
                        @endforeach
                    </tr>
                </thead>
            </table>
            @foreach($replicas as $replica)
                
            @endforeach
        </div>
    </div>
</div>
@endsection