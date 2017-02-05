@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>
                <i class="fa fa-th-list"></i>
                问卷列表

                <small>
                    <a class="btn btn-info btn-sm" href="{{ "/backend/analysis/r/i/channel/{$channel->id}/qrcode" }}" target="_blank">
                        <i class="fa fa-eye"></i>
                        QRCode
                    </a>
                    <a class="btn btn-default btn-sm" href="{{URL::to("/analysis/r/i/channel/{$channel->id}/cxt") . "?public_key={$channel->public_key}"}}" target="_blank">
                        <i class="fa fa-external-link"></i>
                        問卷连结
                        </a>
                </small>
            </h3>
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
                            <th>流水号</th>
                            <th>小孩姓名</th>
                            <th>家长</th>
                            <th>手机</th>
                            <th>E-Mail</th>
                            <th>建立时间</th>
                            <th>最后更新时间</th>
                            <th>状态</th>
                            <th>动作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @each('backend/als_rpt_ib_cxt/index/_tr', $cxts, 'cxt')                        
                    </tbody>
                </table>
            </div>                

            {{ $cxts->links() }}
        </div>
    </div>
</div>
@endsection