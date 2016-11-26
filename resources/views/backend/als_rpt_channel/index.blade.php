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
                <h3>評量頻道列表 
                    <small>您所建立的評量頻道</small>
                </h3>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12"">
                @include('component/flash')
                
                <div class="x_content">
                    <span class="text-muted font-13 m-b-30">
                        SomeDescription
                    </span>
                    <div class="pull-right">
                        <form action="/backend/analysis/r/i/channel" method="post">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-default btn-sm">
                                <i class="fa fa-plus-circle"></i>
                            </button>
                        </form> 
                    </div>
                </div> 
                
                <table id="datatable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>公鑰</th>
                            <th>狀態</th>
                            <th>開始時間</th>
                            <th>截止時間</th>
                            <th>建立時間</th>
                            <th>檢視QRCode</th>
                        </tr>
                    </thead> 
                    <tbody>
                        @foreach ($channels as $channel)
                        <tr>
                            <td>{{ $channel->id }}</td>
                            <td>{{ $channel->public_key }}</td>
                            <td>{{ $channel->getStatusDesc() }}</td>
                            <td>{{ $channel->open_at }}</td>
                            <td>{{ $channel->close_at }}</td>
                            <td>{{ $channel->create_at }}</td>
                            <td>
                                <a href="#">SomeClickableElement</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- @include('analyze/form') --}}
    </div>
    <!-- /page content -->
@endsection

@section('scripts')
<script>
    $('#datatable').DataTable({
        keys: true
    });
</script>
@endsection