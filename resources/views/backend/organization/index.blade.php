@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')
            <h1>
                組織列表

                <small>
                    <a href="/backend/organization/create" class="btn btn-success pull-right">
                        <i class="fa fa-plus-circle"></i>
                        新增組織
                    </a>
                </small>
            </h1>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            组织名称
                        </th>
                        <th>
                            帐号
                        </th>
                        <th>
                            点数
                        </th>
                        <th>
                            联络人
                        </th>
                        <th>
                            拥有人
                        </th>
                        <th>
                            操作
                        </th>
                    </tr>
                </thead>
                @foreach ($organizations as $organization)
                <tbody>
                    <tr>
                        <td>{{ $organization->name }}</td>
                        <td>{{ $organization->account }}</td>
                        <td>
                            @if (0 < $organization->points)
                            <span class="label label-success">
                                {{ $organization->points }}元    
                            </span>
                            @else
                            <span class="label label-danger">
                                {{ $organization->points }}元    
                            </span>
                            @endif                            
                        </td>
                        <td>
                            @if ($organization->contacter)
                                <a class="text-primary" href="/backend/user/{{ $organization->contacter->id }}" target="_blank">
                                    {{ $organization->contacter->name }}
                                </a>
                            @else
                                <span class="label label-warning">尚未指定联络人</span>
                            @endif
                        </td>
                        <td>
                            @if ($organization->owner)
                                <a class="text-primary" href="/backend/user/{{ $organization->owner->id }}">
                                    {{ $organization->owner->name }}
                                </a>
                            @else
                                <span class="label label-warning">尚未指定拥有人</span>
                            @endif
                        </td>
                        <td>
                            <a href="/backend/organization/{{$organization->id}}" class="btn btn-info">
                                <i class="fa fa-eye"></i>
                                检视
                            </a>
                            <a href="/backend/organization/{{$organization->id}}/edit" class="btn btn-primary">
                                <i class="fa fa-edit"></i>
                                编辑
                            </a>
                        </td>
                    </tr>
                </tbody>
                @endforeach
            </table>
        </div>
    </div>
</div>

@endsection

