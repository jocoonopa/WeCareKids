@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('component/flash')

        <h4 class="text-center">
            <small>
                <a class="pull-left btn btn-default btb-sm" href="{{"/backend/amt_diag_group/{$group->id}/amt_diag"}}">
                    回到本大題列表
                </a>    
            </small>
            {{$group->content}} 
    
            <small>
                <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id + 1) . "/amt_diag"}}">
                    切換到下一個大題
                </a>    
            </small>
            <small>
                <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id - 1) . "/amt_diag"}}">
                    切換到上一個大題
                </a>    
            </small>
        </h4>
        <hr>

        {!! Form::model($diag, ['url' => "/backend/amt_diag_group/{$group->id}/amt_diag", 'method' => 'post']) !!}
            @include('backend/amt_diag/component/_form', ['group' => $group, 'diag' => $diag])
        {!! Form::close() !!}
    </div>
</div>
@endsection