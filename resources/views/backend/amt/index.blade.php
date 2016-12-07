@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3> 評測playground</h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <span>網址範例: /backend/amt?command=(656and655)or(657or650and(652and651))</span>
            
            <ul>
                @foreach ($diags as $diag)
                    <li>{{ "{$diag->id}: {$diag->description}" }}</li>
                @endforeach
            </ul>

            <code>
               {{$output}}
            </code>
            
            
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