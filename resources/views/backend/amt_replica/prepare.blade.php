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
                
                <div class="row">
                    <div class="col-md-6">
                        <ul>
                            <li>小圈圈x1</li>
                            <li>笔x1</li>
                            <li>触觉刷x1</li>
                            <li>椅子x2</li>
                            <li>转盘x1</li>
                            <li>滑板车x1</li>
                            <li>体能棒(短)x1</li>
                            <li>体能环(大)x2</li>
                            <li>平衡板x4</li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <ul>
                            <li>全砖x5</li>
                            <li>10厘米障碍x4</li>
                            <li>30厘米障碍x4</li>
                            <li>三角锥x5</li>
                            <li>投球目标(大体能环的大小、离地高度50-60厘米)x1</li>
                            <li>皮球(排球or足球的大小)x1</li>
                            <li>小球(网球的大小)x3</li>
                            <li>量尺x1</li>
                            <li>胶带x1、剪刀x1</li>
                        </ul>        
                    </div>
                </div>
                                        
            @else
                <img src="/images/backend/amt_replica/under_3.jpg" class="img-responsive" alt="低于三岁教具准备图">

                <div class="row">
                    <div class="col-md-6">
                        <ul>
                            <li>笔x1</li>
                            <li>触觉刷x1</li>
                            <li>椅子x2</li>
                            <li>体能垫x1</li>
                            <li>体能环(大)x2</li>
                            <li>平衡板x4</li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <ul>
                            <li>全砖x5</li>
                            <li>三角锥x1</li>
                            <li>投球目标(大体能环的大小、离地高度50-60厘米)x1</li>
                            <li>皮球(排球or足球的大小)x1</li>
                            <li>小球(网球的大小)x3</li>
                            <li>量尺x1</li>
                            <li>胶带x1、剪刀x1</li>
                        </ul>
                    </div>
                </div>                                    
            @endif
        </div>
    </div>
</div>

@endsection