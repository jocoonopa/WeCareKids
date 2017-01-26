@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>
                使用者列表                
            </h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
            
            <a href="/backend/user/create" class="btn btn-default pull-right">
                新增
            </a>

            <table class="table table-striped">
                <thead>
                    <tr>                        
                        <th>姓名</th>
                        <th>组织</th>
                        <th>Email</th>
                        <th>電話</th>
                        <th>問卷</th>
                        <th>評測</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>                        
                        <td>
                            {{$user->name}}

                            <span class="badge">
                                {{$user->getJobTitle()}}
                            </span>
                        </td>
                        <td>
                            @if (is_null($user->organization))
                                <span class="label label-default">未指定所属组织</span>
                            @else
                                <span class="label label-success">
                                    {{$user->organization->name}}
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="mailto:{{$user->email}}">{{$user->email}}</a>                            
                        </td>
                        <td>
                            <a href="tel:{{$user->phone}}">
                                {{$user->phone}}
                            </a>
                        </td>
                        <td>
                            {{ "{$user->cxts->count()}筆" }}
                        </td>
                        <td>
                            {{ "{$user->replicas->count()}筆" }}
                        </td>             
                        <td>
                            @if($user->trashed())
                                <span class="label label-danger">
                                    停用
                                </span>
                            @else
                                <span class="label label-success">
                                    启用
                                </span>
                            @endif
                        </td>
                        <td>
                            @if ($user->trashed())                                
                                {!! Form::model($user, [
                                    'url' => "/backend/user/{$user->id}/restore", 
                                    'method' => 'put', 
                                    'class' => 'pull-right',
                                ]) !!}
                                    <button type="submit" class="btn btn-default btn-sm pull-right" onclick="return confirm('確定啟用{{$user->name}}嗎?')">
                                        <i class="fa fa-check-circle-o"></i>
                                        啟用
                                    </button>
                                {!! Form::close() !!}
                            @else
                                {!! Form::model($user, [
                                    'url' => "/backend/user/{$user->id}",
                                    'method' => 'delete',
                                    'class' => 'pull-right',
                                ]) !!}
                                    <button type="submit" class="btn btn-danger btn-sm pull-right" onclick="return confirm('確定停用{{$user->name}}嗎?')">
                                        <i class="fa fa-remove"></i>
                                        停用
                                    </button>
                                {!! Form::close() !!}
                            @endif 

                            <a href="/backend/user/{{$user->id}}/reset" class="btn btn-default btn-sm pull-right">
                                <i class="fa fa-edit"></i>
                                修改密码
                            </a>

                            <a href="/backend/user/{{$user->id}}/edit" class="btn btn-primary btn-sm pull-right">
                                <i class="fa fa-edit"></i>
                                编辑
                            </a>                              
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection