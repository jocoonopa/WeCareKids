@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
            
            <h3>
                <strong>{{ $organization->name }}</strong>
            </h3>
        </div>

        <div class="col-md-12">
            {!! Form::model($organization, ['url' => "/backend/organization/{$organization->id}", 'method' => 'put']) !!}
                @include('backend/organization/component/_form', ['organization' => $organization, 'users' => $users])
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection

