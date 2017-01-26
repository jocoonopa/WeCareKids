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

            @include('component/flash')
            
            {!! Form::model($user, ['url' => "/backend/user/{$user->id}", 'method' => 'put']) !!}
                @include('backend.user.component._form', compact('user'))
            {!! Form::close() !!}

            <form action="/backend/analysis/r/i/channel/{{$user->channels()->first()->id}}/is_open" method="post" class="pull-right" style="margin-top: -48px;">   
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put" />

                @if($user->channels()->first()->isOpen())
                    <button type="submit" class="btn btn-success pull-right">關閉Channel</button>
                @else
                    <button type="submit" class="btn btn-danger pull-right">打開Channel</button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#name').focus();
</script>
@endpush