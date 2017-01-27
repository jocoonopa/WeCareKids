@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div>
            <h3>
                {{$child->name}}

                <form action="/backend/amt_replica" class="pull-right" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="child_id" value="{{$child->id}}" />
                    <button type="submit" class="pull-right btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>
                        新增评测
                    </button>
                </form>

                <a href="/backend/child" class="btn btn-sm btn-default pull-right">
                    <i class="fa fa-list"></i>

                    孩童列表
                </a>
            </h3>            
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')

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
                        <th>问卷&评测</th>           
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @include('backend.child.index.component._tr', compact('child'))
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <h3>评测历史</h3><hr>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>報告</th>
                        <th>評測</th>
                        <th>問卷</th>
                    </tr>
                </thead>

                <tbody>
                    @each('backend/child/show/_history', $child->replicas, 'replica')                    
                </tbody>
            </table> 
        </div>        
    </div>
</div>
@endsection