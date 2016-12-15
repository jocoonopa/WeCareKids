@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('component/flash')

        <h4 class="text-center">
            {{$group->content}} 
            <small>
                <a class="pull-right btn btn-default btb-sm" href="{{"/backend/amt_diag_group/{$group->id}/amt_diag_standard"}}">
                    回到标准列表
                </a>    
            </small>
        </h4>

        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id + 1) . "/amt_diag_standard"}}">
            切换到下一个大题
        </a>
        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id - 1) . "/amt_diag_standard"}}">
            切换到上一个大题
        </a>    
        <hr>

        {!! Form::model($standard, ['url' => "/backend/amt_diag_group/{$group->id}/amt_diag_standard", 'method' => 'post']) !!}
            @include('backend/amt_diag_standard/component/_form', ['group' => $group, 'standard' => $standard])
        {!! Form::close() !!}

        <a href="/backend/amt_diag_group/{$group->id}/amt_diag_standard/create" class="btn btn-success btn-sm">新增问题</a>       
    </div>
</div>
@endsection