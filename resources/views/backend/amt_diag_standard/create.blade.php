@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('component/flash')

        <h4 class="text-center">
            {{$group->content}} 
            <small>
                <a class="pull-right btn btn-default btb-sm" href="{{"/backend/amt_diag_group/{$group->id}/amt_diag_standard"}}">
                    回到標準列表
                </a>    
            </small>
        </h4>

        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id + 1) . "/amt_diag_standard"}}">
            切換到下一個大題
        </a>
        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id - 1) . "/amt_diag_standard"}}">
            切換到上一個大題
        </a>    
        <hr>

        {!! Form::model($standard, ['url' => "/backend/amt_diag_group/{$group->id}/amt_diag_standard", 'method' => 'post']) !!}
            @include('backend/amt_diag_standard/component/_form', ['group' => $group, 'standard' => $standard])
        {!! Form::close() !!}

        <a href="/backend/amt_diag_group/{$group->id}/amt_diag_standard/create" class="btn btn-success btn-sm">新增問題</a>       
    </div>
</div>
@endsection