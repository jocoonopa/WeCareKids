@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>
                {!! $channel->getStatusDesc(true) !!}
                評量頻道
                <small>
                    {{ "{$channel->open_at->format('Y-m-d')} ~ {$channel->close_at->format('Y-m-d')}" }}
                </small>
            </h3>
        </div>
        <div class="title_right">
            <a href="/backend/analysis/r/i/channel" class="btn btn-sm btn-primary pull-right">
                <i class="fa fa-arrow-circle-o-left"></i>
                回到列表
            </a>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>家長姓名</th>
                        <th>家長電話</th>
                        <th>信箱</th>
                        <th>小朋友姓名</th>
                        <th>私鑰</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($channel->cxts()->get() as $cxt) 
                    <tr>
                        <td>
                            <a href="{{"/backend/analysis/r/i/cxt/{$cxt->id}"}}" target="_blank">
                                {{ $cxt->id }}
                            </a>
                        </td>
                        <td>{{ $cxt->filler_name}}</td>
                        <td>{{ $cxt->phone }}</td>
                        <td>{{ $cxt->email}}</td>
                        <td>{{ $cxt->child_name }}</td>
                        <td>{{ $cxt->private_key }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection