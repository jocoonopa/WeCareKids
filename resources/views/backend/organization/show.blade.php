@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include ('component/flash')

            <h3>
                <strong>交易明细</strong>
                
                <small>
                    <a href="/backend/organization" class="btn btn-sm btn-info pull-right">
                        <i class="fa fa-arrow-circle-left"></i>
                        回到組織列表
                    </a>    
                </small>                
            </h3>

            <div class="well">
                <ul>
                    <li>
                        <label>组织名称:</label> 
                        {{ $organization->name }}
                    </li>

                    <li>
                        <label>帐号:</label>
                        {{ $organization->account }}
                    </li>
                    
                    <li>
                        <label>拥有人:</label>
                        @if ($organization->owner)
                            {{ $organization->owner->name }}
                        @else
                            <span class="label label-default">尚未指定拥有者</span>
                        @endif
                    </li>

                    <li>
                        <label>联络人:</label>
                        @if ($organization->contacter)
                            {{ $organization->contacter->name }}
                        @else
                            <span class="label label-default">尚未指定联络人</span>
                        @endif
                    </li>
                </ul>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>时间</td>
                        <td>日期</td>
                        <td>动作</td>
                        <td>变动金额</td>   
                        <td>剩余金额</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$organization->created_at->format('Y-m-d')}}</td>
                        <td>{{$organization->created_at->format('H:i:s')}}</td>
                        <td>{{ '加盟金赠送储值金' }}</td>
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
                        <td>
                            @if(is_null($usage->usage))
                                {{'已删除'}}
                            @else
                                {{$usage->usage->getUsageDesc()}}
                            @endif
                        </td>
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