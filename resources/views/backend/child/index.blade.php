@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>
                受测者列表
            </h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
            <a href="/backend/child/create" class="btn btn-default pull-right">
                <i class="fa fa-plus"></i>
                新增
            </a>

            <table class="table table-striped">
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
                        <th>問卷&評測</th>           
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @each('backend.child.index.component._tr', $childs, 'child')                    
                </tbody>
            </table>

            {{ $childs->links() }}
        </div>
    </div>
</div>
@endsection