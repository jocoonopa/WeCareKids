@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>评测列表</h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>状态</th>
                            <th>受测者姓名</th>
                            <th>受测时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @each('backend/amt_replica/index/_tr', $replicas, 'replica')
                    </tbody>
                </table>
            </div>
            
            {{ $replicas->links() }}
        </div>
    </div>
</div>
@endsection