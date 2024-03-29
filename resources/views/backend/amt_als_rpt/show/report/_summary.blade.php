<div class="row">
    @if (!is_null($report->cxtBelongs))
    <div class="col-md-4 col-sm-12 col-xs-12">
        <img src="/images/backend/als_rpt_ib_cxt/image010.png" class="img-responsive" style="margin: 0 auto;"><hr/>
        <h2 class="text-center"><strong>感觉处理</strong></h2>

        <p class="lead">{!! nl2br($maxAlsCategory->desc) !!}</p>
    </div>
    @endif

    <div class="@if(!is_null($report->cxtBelongs)) col-md-4 @else col-md-6 @endif col-sm-12 col-xs-12">
        <img src="/images/backend/als_rpt_ib_cxt/image011.png" class="img-responsive" style="margin: 0 auto;"><hr/>
        <h2 class="text-center"><strong>感觉统合</strong></h2>

        <p class="lead">{!! nl2br(\App\Model\AmtCategory::find(\App\Model\AmtCategory::ID_FEEL_INTEGRATE)->commentDescs()->findDescByLevel($iLevel)->first()->desc) !!}
        </p>
    </div>
    <div class="@if(!is_null($report->cxtBelongs)) col-md-4 @else col-md-6 @endif col-sm-12 col-xs-12">
        <img src="/images/backend/als_rpt_ib_cxt/image012.png" class="img-responsive" style="margin: 0 auto;"><hr/>
        <h2 class="text-center"><strong>粗大动作</strong></h2>

        <p class="lead">{!! nl2br(\App\Model\AmtCategory::find(\App\Model\AmtCategory::ID_ROUGH_ACTION)->commentDescs()->findDescByLevel($eLevel)->first()->desc) !!}
        </p>
    </div>
</div>
    