@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
            <h1>
                加盟商列表

                <small>
                    <a href="/backend/organization/create" class="btn btn-success pull-right">
                        <i class="fa fa-plus-circle"></i>
                        新增加盟商
                    </a>
                </small>
            </h1>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>组织名称</th>
                        <th>地区</th>
                        <th>帐号</th>
                        <th>点数</th>
                        <th>联络人</th>
                        <th>拥有人</th>
                        <th>教师数</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @each('backend/organization/index/_tr', $organizations, 'organization')              
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

