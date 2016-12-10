@extends('layouts.blank')

@push('stylesheets')
<style>

.analyze-border {
    border-style: solid;
    border-color: #000;
    border-width: 1px;
}
.analyze-border td {
    text-align: center;
    border: none !important;
}

</style>
@endpush

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')

            @include('backend/amt_als_rpt/show/report', ['report' => $report, 'child' => $report->replica->child])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/js/chart.min.js"></script>
@include('backend/amt_als_rpt/show/script', ['replica' => $report->replica])
@endpush