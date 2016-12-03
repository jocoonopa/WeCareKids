@extends('layouts.land')

@section('main_container')

<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        <div class="text-center">    
            @include('component/qrcode', [
                'size' => $size, 
                'string' => URL::to("/analysis/r/i/channel/{$channel->id}/cxt") . "?public_key={$channel->public_key}"
            ])
        </div>

        <div class="text-center">
            <a href="{{ Request::url() . '?size=500'}}" class="btn btn-large btn-default">大</a>
            <a href="{{ Request::url() . '?size=350'}}" class="btn btn-large btn-default">中</a>
            <a href="{{ Request::url() . '?size=150'}}" class="btn btn-large btn-default">小</a>
        </div>
    </div>
</div>

@endsection
