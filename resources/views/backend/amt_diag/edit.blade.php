@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('component/flash')

        <h4 class="text-center">
            {{$group->content}} 
            <small>
                <a class="pull-right btn btn-default btb-sm" href="{{"/backend/amt_diag_group/" . ($group->id) . "/amt_diag"}}">
                    回到問題列表
                </a>    
            </small>
        </h4>
        <hr>

        {!! Form::model($diag, ['url' => "/backend/amt_diag_group/{$group->id}/amt_diag/{$diag->id}", 'method' => 'put']) !!}
            @include('backend/amt_diag/component/_form', ['group' => $group, 'diag' => $diag])
        {!! Form::close() !!}

        <ul>
            @foreach ($diag->standards as $standard)
                <li>
                    <a href="{{"/backend/amt_diag_group/{$group->id}/amt_diag_standard/{$standard->id}/edit"}}">
                    {{$standard->id}}:{{$standard->condition_value}}</a>
                    {{$standard->min_level}}~{{$standard->max_level}}
                </li>
            @endforeach

            <li>
                <a class="btn btn-success btn-sm" href="{{"/backend/amt_diag_group/{$group->id}/amt_diag_standard/create"}}">新增標準</a>
            </li>
        </ul>
    </div>
</div>
@endsection