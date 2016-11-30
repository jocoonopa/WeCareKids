@extends('layouts.land')

@push('stylesheets')
<style>
/* — § ○｜*/
.symbol01 {
    background-color: #F44336;
    color: #eaeaea;
}
.symbol02 {
    background-color: #2ecc71;
    color: #eaeaea;
}
.symbol03 {
    background-color: #9b59b6;
    color: #eaeaea;
}
.symbol04 {
    background-color: #3498db;
    color: #eaeaea;
}
</style>
@endpush

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('frontend/als_rpt_ib_cxt/index/form')
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="/js/frontend/als_rpt_ib_cxt/dom-event.js"></script>
<script type="text/javascript" src="/js/frontend/als_rpt_ib_cxt/cxt-update.js"></script>
@endpush