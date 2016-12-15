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
                <a href="/backend/amt_diag_group/{{$group->id}}/amt_diag/create" class="pull-right btn btn-primary">新增问题</a>
            </small>
        </h3>

        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id + 1) . "/amt_diag"}}">
            切换到下一个大题
        </a>
        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id - 1) . "/amt_diag"}}">
            切换到上一个大题
        </a>    

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>问题流水号</th>
                    <th>类型</th>
                    <th>问题描述</th>
                    <th>可用值</th>
                    <th>动作</th>
                    <th>关联标准</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($group->diags()->get() as $diag)
                <tr>
                    <td>{{ $diag->id }}</td>
                    <td>{{ $diag->getTypeName() }}</td>
                    <td>{{ $diag->description }}</td>
                    <td>{{ $diag->available_value}}</td>
                    <td>
                        <a href="/backend/amt_diag_group/{{$group->id}}/amt_diag/{{$diag->id}}/edit" class="btn btn-default">
                            编辑
                        </a>
                    </td>
                    <td>
                        <ul>
                            @foreach ($diag->standards as $standard)
                                <li>
                                    <a href="{{"/backend/amt_diag_group/{$group->id}/amt_diag_standard/{$standard->id}/edit"}}">{{$standard->id}}:{{$standard->condition_value}}</a>
                                    {{$standard->min_level}}~{{$standard->max_level}}
                                </li>
                            @endforeach

                            <li>
                                <a class="btn btn-success btn-sm" href="{{"/backend/amt_diag_group/{$group->id}/amt_diag_standard/create"}}">新增标准</a>
                            </li>
                        </ul>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection