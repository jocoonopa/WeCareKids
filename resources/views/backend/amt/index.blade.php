@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>評測管理</h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <ul>  
                @foreach ($amts as $amt)       
                <li>
                    <a class="btn btn-info" href="/backend/amt/{{$amt->id}}">{{ "v{$amt->id}" }}</a>
                </li>  
                @endforeach
            </ul>            
        </div>
    </div>
</div>
@endsection