<div class="table-responsive">
    <table class="table table-borderedr">
        <tbody>
            <tr>
                @if (!is_null($report->cxtBelongs))
                <th class="text-center" style="width: 33%;">
                    <img src="/images/backend/als_rpt_ib_cxt/image010.png" class="img-responsive" style="margin: 0 auto;"><br/>
                    <h2><strong>感覺處理</strong></h2>
                </th>
                @endif
                <th class="text-center" @if(is_null($report->cxtBelongs)) style="width: 50%;"@else style="width: 33%;" @endif>
                    <img src="/images/backend/als_rpt_ib_cxt/image011.png" class="img-responsive" style="margin: 0 auto;"><br/>
                    <h2><strong>感覺統合</strong></h2>
                </th>
                <th class="text-center" @if(is_null($report->cxtBelongs)) style="width: 50%;"@else style="width: 33%;" @endif>
                    <img src="/images/backend/als_rpt_ib_cxt/image012.png" class="img-responsive" style="margin: 0 auto;"><br/>
                    <h2><strong>粗大動作</strong></h2>
                </th>
            </tr>
            <tr>
                @if (!is_null($report->cxtBelongs))
                <td class="text-center">
                    {{$maxAlsCategory->name}}處於
                    {{ "閾值[{$maxAlsCategory->thread}]-[$maxAlsCategory->type]" }} 
                </td>
                @endif
                <td class="text-center">Level {{$iLevel}}</td>
                <td class="text-center">Level {{$eLevel}}</td>
            </tr>
            <tr>
                @if (!is_null($report->cxtBelongs))
                    <td>
                        <p>{!! nl2br($maxAlsCategory->desc) !!}</p>
                    </td>
                @endif
                <td>
                    <p>{!! nl2br(\App\Model\AmtCategory::find(\App\Model\AmtCategory::ID_FEEL_INTEGRATE)->commentDescs()->findDescByLevel($iLevel)->first()->desc) !!}
                    </p>
                </td>
                <td>
                    <p>{!! nl2br(\App\Model\AmtCategory::find(\App\Model\AmtCategory::ID_ROUGH_ACTION)->commentDescs()->findDescByLevel($eLevel)->first()->desc) !!}
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>