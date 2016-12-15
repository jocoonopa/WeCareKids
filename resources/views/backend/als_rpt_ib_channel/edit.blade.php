@extends('layouts.blank')

@push('stylesheets')
<link rel="stylesheet" href="/css/daterangepicker.css">
@endpush

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>编辑评量频道列表{{$channel->id}}</h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
            
            @include('backend/als_rpt_ib_channel/edit/form')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/daterangepicker.js"></script>
<script src="/js/backend/als_rpt_ib_cxt/form.js"></script>
@endpush