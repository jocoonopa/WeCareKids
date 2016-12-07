@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12"">
            @include('component/flash')
            
            {!! Form::model($child, ['url' => "/backend/child/{$child->id}", 'method' => 'put']) !!}
                @include('backend/child/component/_form')
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection