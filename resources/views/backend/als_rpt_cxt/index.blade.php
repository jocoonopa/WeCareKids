@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('backend/als_rpt_cxt/index/form')
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="/js/frontend/als_rpt_ib_cxt/dom-event.js"></script>
<script type="text/javascript" src="/js/frontend/als_rpt_ib_cxt/cxt-update.js"></script>
@endpush