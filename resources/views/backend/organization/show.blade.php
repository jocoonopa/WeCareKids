@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <h3>目前剩餘點數: {{ $organization->points }}</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>流水號</td>
                        <td>老師</td>
                        <td>小朋友</td>
                        <td>動作</td>
                        <td>時間</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($organization->usages as $usage)
                    <tr>
                        <td>{{$usage->id}}</td>
                        <td>{{$usage->user->name}}</td>
                        <td>{{$usage->child->name}}</td>
                        <td>{{$usage->usage->getUsageDesc()}}</td>
                        <td>{{$usage->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection