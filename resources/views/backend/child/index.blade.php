@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>
                <i class="fa fa-th-list"></i>
                孩童列表
            </h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('component/flash')
            <a href="/backend/child/create" class="btn btn-success btn-sm pull-right">
                <i class="fa fa-plus"></i>
                新增
            </a>

            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <form action="/backend/child" method="get">
                        <div class="input-group">
                            <input type="text" name="name" class="form-control" value="{{Request::get('name')}}">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-md-3 col-sm-0"></div>
            </div>        
            
            <div class="table-responsive">
                <table id="childs-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>姓名</th>
                            <th>生日</th>
                            <th>年龄</th>
                            <th>家长姓名</th>
                            <th>电话</th>
                            <th>Email</th>
                            <th>问卷</th>
                            <th>评测</th>
                            <th>教师</th>               
                            <th>问卷&评测</th>           
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @each('backend/child/index/component/_tr', $childs, 'child')                    
                    </tbody>
                </table>
            </div>
            

            {{ $childs->appends(['name' => Request::get('name')])->links() }}
        </div>
    </div>
</div>
@endsection