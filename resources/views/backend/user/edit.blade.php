@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <h1>
                {{$user->name}}
                <small>编辑</small>

                <small>
                    <a href="/backend/user" class="btn btn-primary btn-sm pull-right">
                        <i class="fa fa-arrow-circle-left"></i>
                        回到列表
                    </a>
                </small>
            </h1>
            
            {!! Form::model($user, ['url' => "/backend/user/{$user->id}", 'method' => 'put']) !!}
                @include('backend.user.component._form', compact('user'))
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#name').focus();
</script>
@endpush