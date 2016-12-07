@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>
                {{$child->name}}

                <small class="pull-right">
                    <form action="/backend/amt_replica" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="child_id" value="{{$child->id}}" />
                        <button type="submit" class="pull-right btn btn-default">新增評測</button>
                    </form>
                </small>
            </h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')

            <ul>
                <li>姓名: {{ $child->name }}</li>
                <li>生日: {{ $child->birthday->format('Y-m-d') }}</li>
                <li>學校: {{ $child->school_name }}</li>
                <li>身份證: {{ $child->identifier }}</li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <h3>評測歷史</h3><hr>

            <table class="table table-striped">
                <tbody>
                    @foreach ($child->replicas()->get() as $replica)
                    <tr>
                        <td>
                            <a href="/backend/amt_replica/{{$replica->id}}" target="_blank">
                                {{$replica->id}}
                            </a>                            
                        </td>
                        <td>{{$replica->created_at->format('Y-m-d H:i:s')}}</td>
                        <td>{{$replica->updated_at->format('Y-m-d H:i:s')}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table> 
        </div>        
    </div>
</div>
@endsection