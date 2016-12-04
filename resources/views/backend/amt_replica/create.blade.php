@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
           <form action="/backend/amt_replica" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <button type="submit" class="btn btn-default">
                        Create New One
                    </button>
                </div>
           </form>
        </div>
    </div>
</div>
@endsection