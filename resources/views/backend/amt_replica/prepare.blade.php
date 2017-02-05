@extends('layouts.land')

@section('main_container')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12">
            <h3>
                <a href="/backend/amt_replica/{{$replica->id}}/edit" class="btn btn-info">
                    <i class="fa fa-arrow-right"></i>
                    前往作答
                </a>
            </h3>
            @if ($isAgeAboveThree)
                <img src="/images/backend/amt_replica/3_or_above.jpg" class="img-responsive" alt="三岁以上教具准备图">
            @else
                <img src="/images/backend/amt_replica/under_3.jpg" class="img-responsive" alt="低于三岁教具准备图">
            @endif
        </div>
    </div>
</div>

@endsection