@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>
                问卷列表
                <small>
                    <a class="btn btn-info" href="{{ "/backend/analysis/r/i/channel/{$channel->id}/qrcode" }}">
                        <i class="fa fa-eye"></i>
                        QRcode
                    </a>
                    <a href="{{URL::to("/analysis/r/i/channel/{$channel->id}/cxt") . "?public_key={$channel->public_key}"}}">連結</a>
                </small>
            </h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">            
            <table class="table">
                <thead>
                    <tr>
                        <th>流水号</th>
                        <th>小孩姓名</th>
                        <th>家长</th>
                        <th>建立时间</th>
                        <th>最后更新时间</th>
                        <th>状态</th>
                        <th>动作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cxts as $cxt)
                    <tr>
                        <td>{{ $cxt->id }}</td>
                        <td>{{ $cxt->child_name }}</td>
                        <td>{{ $cxt->filler_name }}</td>
                        <td>{{ $cxt->created_at }}</td>
                        <td>{{ $cxt->updated_at }}</td>
                        <td>
                            <span class="label {{\Wck::getCxtStatusLabel($cxt)}}">
                                {{ $cxt->getStatusDesc() }}
                            </span>                            
                        </td>
                        <td>
                            <a href="{{"/backend/analysis/r/i/cxt/{$cxt->id}"}}" target="_blank">
                                <i class="fa fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $cxts->links() }}
        </div>
    </div>
</div>
@endsection