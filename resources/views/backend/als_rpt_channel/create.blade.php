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
    <div class="page-title">
        <div class="title_left">
            <h3>評量頻道新增頁面</h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <div class="x_content">
                <span class="text-muted font-13 m-b-30">
                    SomeDescription
                </span>
            </div> 
            
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button type="submit" class="btn btn-primary">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>        
</div>
<!-- /page content -->

@endsection

@section('scripts')

@endsection