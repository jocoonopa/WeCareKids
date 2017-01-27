@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12">
            <h3>
                <i class="fa fa-th-list"></i>
                教师列表   
                
                @can('create', \App\Model\User::class)
                    <small>
                        <a href="/backend/user/create" class="btn btn-success btn-sm pull-right">
                            <i class="fa fa-plus-circle"></i>
                            新增
                        </a>  
                    </small> 
                @endcan                                          
            </h3>
            <div class="clearfix"></div>
        </div>
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
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
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
                        @each('backend/user/index/_tr', $users, 'user')                        
                    </tbody>
                </table>
            </div>
            
            {{ $users->appends(['organization_id' => Request::get('organization_id')])->links() }}
        </div>
    </div>
</div>
@endsection