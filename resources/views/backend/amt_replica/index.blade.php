@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3> 評測replica playground</h3>
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
                            <form action="/backend/amt_replica/{{$replica->id}}" method="post">
                                {{csrf_field()}}
                                
                                <input type="hidden" name="_method" value="delete">
                                
                                <button class="btn btn-default btn-sm">
                                    刪除{{$replica->id}}
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