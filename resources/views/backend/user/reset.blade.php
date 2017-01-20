@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
            <h1>
                {{$user->name}}
                <small>修改密码</small>
            </h1>
            
            {!! Form::model($user, ['url' => "/backend/user/{$user->id}/reset", 'method' => 'put']) !!}
                <div class="form-group">
                    <label for="password">请输入新密码</label>
                    <input id="password" type="password" class="form-control" name="password" />
                </div>

                <div class="form-group">
                    <label for="password_confirmation">再次确认密码</label>
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation"/>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        确认
                    </button>

                    <a href="/backend/user/{{$user->id}}" class="pull-right btn btn-default">
                        取消
                    </a>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#password').focus();
</script>
@endpush