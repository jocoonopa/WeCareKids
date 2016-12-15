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
        <hr>

        {!! Form::model($standard, ['url' => "/backend/amt_diag_group/{$group->id}/amt_diag_standard/{$standard->id}", 'method' => 'put']) !!}
            @include('backend/amt_diag_standard/component/_form', ['group' => $group, 'standard' => $standard])
        {!! Form::close() !!}

        <a class="btn btn-success btn-sm" href="{{"/backend/amt_diag_group/{$group->id}/amt_diag/{$standard->diag->id}/edit"}}">
            编辑{{ $standard->diag->id }}:{{ $standard->diag->description }}
        </a>        
    </div>
</div>
@endsection