@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <h1>
                v{{$amt->id}}
                <small>
                    <a href="/backend/amt/{{$amt->id}}/map" class="pull-right btn btn-default" target="_blank">
                        全圖
                    </a>
                </small>
            </h1>
            
            <ul>
                @include('backend/amt/component/_menu', ['menus' => $menus, 'amt' => $amt])
            </ul>    
        </div>
    </div>
</div>     
@endsection