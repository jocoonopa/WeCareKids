@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>
                受測者列表
            </h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
            <a href="/backend/child/create" class="btn btn-default pull-right">
                新增
            </a>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>姓名</th>
                        <th>生日</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($childs as $child)
                    <tr>
                        <td>
                            <a href="/backend/child/{{$child->id}}" target="_blank">
                                {{$child->id}}
                            </a>                            
                        </td>
                        <td>{{$child->name}}</td>
                        <td>{{$child->birthday->format('Y-m-d')}}</td>
                        <td>
                            <a href="{{"/backend/child/{$child->id}/edit"}}" class="pull-right btn btn-default">
                                <i class="fa fa-edit"></i>
                                編輯
                            </a>
                            @if (0 === $child->replicas()->where('status', \App\Model\AmtReplica::STATUS_ORIGIN_ID)->count())
                            <form action="/backend/amt_replica" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="child_id" value="{{$child->id}}" />
                                <button type="submit" class="pull-right btn btn-default">新增評測</button>
                            </form>
                            @else
                            <a href="/backend/amt_replica/{{$child->replicas()->where('status', \App\Model\AmtReplica::STATUS_ORIGIN_ID)->first()->id}}/edit" class="pull-right btn btn-info" target="_blank">繼續評測</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection