@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        @include('backend/als_rpt_ib_channel/show/pageTitle')
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
           @include('backend/als_rpt_ib_channel/show/table', ['channel' => $channel])
        </div>
    </div>
</div>
@endsection