@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>剖析報告: {{ $cxt->id }}</h3>
        </div>
        <div class="title_right">
            <a href="/backend/analysis/r/i/cxt" class="btn btn-sm btn-primary pull-right">
                <i class="fa fa-arrow-circle-o-left"></i>
                回到列表
            </a>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            SomeContent
        </div>
    </div>  
</div>
@endsection