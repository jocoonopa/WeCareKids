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
                            <a href="/backend/user/{{$organization->owner->id}}">
                                {{ $organization->owner->name }}
                            </a>                            
                        @else
                            <span class="label label-default">尚未指定拥有者</span>
                        @endif
                    </li>

                    <li>
                        <label>联络人:</label>
                        @if ($organization->contacter)
                            <a href="/backend/user/{{ $organization->contacter->id }}">
                                {{ $organization->contacter->name }}
                            </a>                            
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
                    @each('backend.organization.show._tr', $usages, 'usage')
                </tbody>
            </table>

            {{ $usages->links() }}
        </div>
    </div>
</div>
@endsection