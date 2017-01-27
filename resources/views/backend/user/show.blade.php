@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <h1>
                {{$user->name}}

                <small>
                    <a href="/backend/user" class="btn btn-info btn-sm pull-right">
                        <i class="fa fa-list"></i>
                        教师列表
                    </a>
                </small>
            </h1>
           
            <table class="table">
                <tbody>
                    <tr>
                        <td>所属单位:</td>
                        <td>
                            @if (is_null($user->organization))
                                <p class="text-muted">
                                    未指定所属单位
                                </p>
                            @else
                                <span>{{$user->organization->name}}</span>
                            @endif
                        </td>                    
                    </tr>            
                    <tr>
                        <td>Email:</td>
                        <td>
                            <a href="mailto: {{$user->email}}">
                                {{$user->email}}
                            </a>
                        </td>  
                    </tr>
                    <tr>
                        <td>电话:</td>
                        <td>
                            <a href="tel: {{$user->phone}}">
                                {{$user->phone}}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>权限:</td>
                        <td>
                            {{$user->getJobTitle()}}
                        </td>                
                    </tr>
                    <tr>
                        <td>建立时间:</td>
                        <td>{{$user->created_at->format('Y-m-d')}}</td>
                    </tr>                    
                    <tr>
                        <td>状态:</td>
                        <td>
                            @if ($user->trashed())
                                <p class="label label-danger">{{'停用'}}</p>
                            @else
                                <p class="label label-success">{{'启用'}}</p>
                            @endif
                        </td>                    
                    </tr>    
                </tbody>                
            </table>
            
            @can('update', $user)
                <a href="/backend/user/{{$user->id}}/edit" class="btn btn-primary btn-sm">
                    <i class="fa fa-edit"></i>
                    编辑
                </a>

                <a href="/backend/user/{{$user->id}}/reset" class="btn btn-default btn-sm">
                    <i class="fa fa-edit"></i>
                    修改密码
                </a>
            @endcan
        </div>
    </div>
</div>
@endsection