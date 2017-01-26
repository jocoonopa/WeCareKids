@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>评测列表</h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>状态</th>
                        <th>版本</th>   
                        <th>受测者姓名</th>
                        <th>受测时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($replicas as $replica)
                    <tr>
                        <td>
                            <a href="/backend/amt_replica/{{$replica->id}}" target="_blank">{{$replica->id}}</a>

                            @if ($replica->isDone())
                                <span class="label label-success">完成</span>
                            @else
                                <span class="label label-default">未开始</span>
                            @endif
                        </td>
                        <td>
                            <span class="label label-info">V{{$replica->amt_id}}</span> 
                        </td>
                        <td>
                            <a href="/backend/child/{{$replica->child->id}}">
                                {{ $replica->child->name }}
                            </a>                            
                        </td>
                        <td>
                            {{ $replica->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td>                            
                            <form action="/backend/amt_replica/{{$replica->id}}" class="pull-right" method="post" onsubmit="return confirm('确定删除吗?');">
                                {{csrf_field()}}
                                
                                <input type="hidden" name="_method" value="delete">
                                
                                <button class="btn btn-danger btn-sm pull-right" >
                                    <i class="fa fa-remove"></i>
                                    删除
                                </button>
                            </form>

                            <a href="/backend/amt_replica/{{$replica->id}}" class="btn btn-primary btn-sm pull-right" target="_blank">
                                <i class="fa fa-eye"></i>
                                前往
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $replicas->links() }}
        </div>
    </div>
</div>
@endsection