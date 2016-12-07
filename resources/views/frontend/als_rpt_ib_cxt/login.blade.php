@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('component/flash')

        <form action="/analysis/r/i/channel/{{$channel->id}}/cxt" method="post">
            {{csrf_field()}}

            <div class="form-group">
                <label for="phone">電話號碼:</label>

                <input id="phone" name="phone" type="number" class="form-control" plcaeholder="請輸入電話號碼" value="" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-default">
                    確定
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
