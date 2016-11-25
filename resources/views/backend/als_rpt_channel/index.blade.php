@extends('layouts.blank')

@push('stylesheets')
    <!-- iCheck -->
    <link href="/css/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- jVectorMap -->
    <link href="/css/jquery-jvectormap-2.0.3.css" rel="stylesheet"/>
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
        {{-- @include('analyze/form') --}}
    </div>
    <!-- /page content -->

    <!-- footer content -->
    <footer>
    </footer>
    <!-- /footer content -->
@endsection