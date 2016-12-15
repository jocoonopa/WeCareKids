@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('component/flash')
        
        <h3>
            <small>
                <a href="/backend/amt/{{$group->amt->id}}" class="pull-left btn btn-default">大题列表</a>
            </small>

            {{$group->content}}

            <small>
                <a href="/backend/amt_diag_group/{{$group->id}}/amt_diag_standard/create" class="pull-right btn btn-primary">新增标准</a>
            </small>
        </h3>

        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id + 1) . "/amt_diag_standard"}}">
            切换到下一个大题
        </a>
        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id - 1) . "/amt_diag_standard"}}">
            切换到上一个大题
        </a>    

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>标准流水号</th>
                    <th>对应题目描述</th>
                    <th>level range(之后仅用来参考, level 相关处理之后都是cell处理)</th>
                    <th>条件值</th>
                    <th>动作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($group->standards as $standard)
                <tr>
                    <td>{{ $standard->id }}</td>
                    <td>
                        <a href="{{"/backend/amt_diag_group/{$group->id}/amt_diag/{$standard->diag->id}/edit"}}">
                            {{$standard->diag->id}}:</a>{{ "{$standard->diag->description}" }}
                        </td>
                    <td>{{ "{$standard->min_level}~{$standard->max_level}" }}</td>
                    <td>{{ $standard->condition_value}}</td>
                    <td>
                        <a href="/backend/amt_diag_group/{{$group->id}}/amt_diag_standard/{{$standard->id}}/edit" class="btn btn-success btn-sm">
                            编辑
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection