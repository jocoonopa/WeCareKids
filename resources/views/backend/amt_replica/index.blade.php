@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3> 評測replica playground</h3>

            <small>
                <form action="/backend/amt_replica" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <button type="submit" class="btn btn-default">
                            Create New One
                        </button>
                    </div>
               </form>
            </small>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')

            <table class="table table-striped">
                <tbody>
                    @foreach ($replicas as $replica)
                    <tr>
                        <td>
                            <a href="/backend/amt_replica/{{$replica->id}}" target="_blank">{{$replica->id}}</a>

                            @if ($replica->isDone())
                                <span class="label label-success">完成</span>
                            @else
                                <span class="label label-default">未開始</span>
                            @endif
                        </td>
                        <td>
                            <form action="/backend/amt_replica/{{$replica->id}}" method="post" onsubmit="return confirm('確定刪除嗎?');">
                                {{csrf_field()}}
                                
                                <input type="hidden" name="_method" value="delete">
                                
                                <button class="btn btn-danger btn-sm pull-right" >
                                    刪除
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection