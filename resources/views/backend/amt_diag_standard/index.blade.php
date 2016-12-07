@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('component/flash')
        
        <h3>
            <small>
                <a href="/backend/amt/{{$group->amt->id}}" class="pull-left btn btn-default">大題列表</a>
            </small>

            {{$group->content}}

            <small>
                <a href="/backend/amt_diag_group/{{$group->id}}/amt_diag_standard/create" class="pull-right btn btn-primary">新增標準</a>
            </small>
        </h3>

        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id + 1) . "/amt_diag_standard"}}">
            切換到下一個大題
        </a>
        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id - 1) . "/amt_diag_standard"}}">
            切換到上一個大題
        </a>    

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>標準流水號</th>
                    <th>對應題目描述</th>
                    <th>level range(之後僅用來參考, level 相關處理之後都是cell處理)</th>
                    <th>條件值</th>
                    <th>動作</th>
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
                            編輯
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection