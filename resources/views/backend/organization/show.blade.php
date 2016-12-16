@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <h3>目前剩餘點數: {{ $organization->points }}</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>時間</td>
                        <td>日期</td>
                        <td>動作</td>
                        <td>變動金額</td>   
                        <td>剩餘金額</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$organization->created_at->format('Y-m-d')}}</td>
                        <td>{{$organization->created_at->format('H:i:s')}}</td>
                        <td>{{ '加盟金贈送儲值金' }}</td>
                        <td>
                            <span class="label label-success">
                                {{ '+' . \App\Model\Organization::INIT_BENEFIT }}
                            </span>                            
                        </td>
                        <td>{{ \App\Model\Organization::INIT_BENEFIT }}</td>
                    </tr>
                    @foreach ($organization->usages as $usage)
                    <tr>
                        <td>{{$usage->created_at->format('Y-m-d')}}</td>
                        <td>{{$usage->created_at->format('H:i:s')}}</td>
                        <td>{{$usage->usage->getUsageDesc()}}</td>
                        <td>{!! $usage->getVarietyDesc() !!}</td>
                        <td>{{$usage->current_remain}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection