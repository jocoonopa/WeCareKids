@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12"">
            @include('component/flash')
            
            {!! Form::model($group, ['url' => "/backend/amt/{$amt->id}/amt_diag_group/{$group->id}", 'method' => 'put']) !!}
                @include('backend/amt_diag_group/_form', compact('group'))
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection