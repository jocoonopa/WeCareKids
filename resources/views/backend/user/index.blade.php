@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div>
            <h3>
                教师列表   
                
                <small>
                    <a href="/backend/user/create" class="btn btn-success btn-sm pull-right">
                        <i class="fa fa-plus-circle"></i>
                        新增
                    </a>  
                </small>                                           
            </h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
                
            @if (Auth::user()->isSuper())
            <form action="/backend/user" class="form-horizontal form-label-left" method="get">
                <div class="input-group">
                    <select name="organization_id" id="organization_id" class="form-control">
                        <option value="0">--</option>

                        @foreach (\App\Model\Organization::all() as $organization)
                            <option value="{{ $organization->id }}" @if ($organization->id == Request::get('organization_id')) selected @endif>
                                {{ $organization->name }}
                            </option>
                        @endforeach
                    </select>

                    <div class="input-group-btn">
                        <button type="submib" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>                
            </form>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>                        
                        <th>姓名</th>
                        <th>组织</th>
                        <th>Email</th>
                        <th>电话</th>
                        <th>问卷</th>
                        <th>评测</th>
                        <th>状态</th>
                        @if (Auth::user()->isSuper())
                            <th>操作</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>                        
                        <td>
                            <a href="/backend/user/{{$user->id}}">
                                {{$user->name}}
                            </a>

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
                            {{ "{$user->cxts->count()}笔" }}
                        </td>
                        <td>
                            {{ "{$user->replicas->count()}笔" }}
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
                        @if (Auth::user()->isSuper())
                        <td>
                             <a href="/backend/user/{{$user->id}}/reset" class="btn btn-default btn-sm pull-left">
                                <i class="fa fa-edit"></i>
                                修改密码
                            </a>

                            <a href="/backend/user/{{$user->id}}/edit" class="btn btn-primary btn-sm pull-left">
                                <i class="fa fa-edit"></i>
                                编辑
                            </a>       
                            @if ($user->trashed())                                
                                {!! Form::model($user, [
                                    'url' => "/backend/user/{$user->id}/restore", 
                                    'method' => 'put', 
                                    'class' => 'pull-left',
                                ]) !!}
                                    <button type="submit" class="btn btn-default btn-sm pull-left" onclick="return confirm('确定启用{{$user->name}}吗?')">
                                        <i class="fa fa-check-circle-o"></i>
                                        启用
                                    </button>
                                {!! Form::close() !!}
                            @else
                                {!! Form::model($user, [
                                    'url' => "/backend/user/{$user->id}",
                                    'method' => 'delete',
                                    'class' => 'pull-left',
                                ]) !!}
                                    <button type="submit" class="btn btn-danger btn-sm pull-left" onclick="return confirm('确定停用{{$user->name}}吗?')">
                                        <i class="fa fa-remove"></i>
                                        停用
                                    </button>
                                {!! Form::close() !!}
                            @endif                                                 
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $users->appends(['organization_id' => Request::get('organization_id')])->links() }}
        </div>
    </div>
</div>
@endsection