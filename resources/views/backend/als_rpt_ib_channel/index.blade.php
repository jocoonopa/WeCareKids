@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>评量频道列表 
                <small>您所建立的评量频道</small>
            </h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
            
            @include('backend/als_rpt_ib_channel/index/table')

            {{ $channels->links() }}
        </div>
    </div>
</div>
@endsection