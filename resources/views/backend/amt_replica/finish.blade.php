@extends('layouts.land')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12"">
            @include('component/flash')

            <hr/>

            <a id="link" class="btn btn-default btn-large" href="/backend/amt_replica/{{$replica->id}}">
                前往检视结果<small id="count"></small>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    var timeSeconds = 5;
    var interval = 1000;

    function countDown() {
        $('#count').text(timeSeconds);

        return setTimeout(function () {
            if (0 === timeSeconds) {
                return window.location.href = "/backend/amt_replica/{{$replica->id}}";
            }

            timeSeconds --;

            return countDown();
        }, interval);
    }

    countDown();
});
</script>
@endpush
