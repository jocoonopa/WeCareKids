<?php $ieLevel = floor(($iLevel + $eLevel)/2);?>
<?php $ranges = \App\Model\Child::getYearMonthRangeFromMap($ieLevel); ?>
<h1 class="text-center" style="color:#f0ad4e;">
    <strong>智能運動能力等級：Level {{ $ieLevel}}</strong>
</h1>

<p class="lead">智能運動為孩子的感覺統合與粗大動作之發展狀況，從孩子大腦整合與肢體協調的了解孩子在體能方面的發展潛能。
    <br/> 您的孩子在智能運動項目為Level {{$ieLevel}}，發展年齡為
    {{ array_get($ranges, 0) . '~' . array_get($ranges, 1) }}
    ，與您的實際年齡相比，發展年齡與實際年齡
    
    @if ((int) $ieLevel > (int) $defaultLevel)
    超前6個月：相較下發展潛力高，應增加給予相關活動，以培育為優勢能力。
    @endif

    @if ((int) $ieLevel === (int) $defaultLevel)
    落差小於6個月：相符合，應持續給予相關活動，以維持智能運動的整體發展。
    @endif

    @if ((int) $ieLevel < (int) $defaultLevel)
    稍慢6個月：相較下發展落後，應積極給予相關活動，及早補強，避免影響學習效率。
    @endif
</p>
<br/>

{{-- 感觉统合 --}}
<h1 class="text-center">
    <strong>感觉统合</strong>
</h1>
<p class="lead">感覺統合是大腦神經系統組織、詮釋周遭環境的感覺訊息，讓我們對環境刺激有正確的認識後，才能對外界做適當的互動和學習。若我們對環境的訊息接收不當，易出現感覺調節困難、專注力不集中、學習困難等狀況。</p>
<div class="table-responsive text-center">
    <table class="table table-bordered">
        <thead>
            <th>評測能力</th>
            <th colspan="{{ \App\Model\AmtAlsRpt::TOTAL_DEEP_STEP - 1 }}">評測項目</th>
            <th>實際能力等級</th>
            <th>同齡能力等級</th>
        </thead>
        <tbody>
            @include('backend/amt_als_rpt/show/report/component/tdRec', [
                'childCategory' => \App\Model\AmtCategory::find(\App\Model\AmtCategory::ID_FEEL_INTEGRATE), 
                'colSpanCount' => \App\Model\AmtAlsRpt::TOTAL_DEEP_STEP
            ])  
        </tbody>
    </table>
</div>

{{-- 粗大動作 --}}
<h1 class="text-center">
    <strong>粗大动作</strong>
</h1>
<p class="lead">動作發展是指孩子的神經與肌肉骨骼系統會隨著年齡的增加，出現簡單至複雜的大肌肉活動，如：嬰幼兒翻身至成人踢球。每個年齡皆有黃金動作發展里程碑，而動作發展的表現不單是肌肉骨骼的成熟，還包括感覺系統整合、心肺功能、心理認知發展等多元系統的互動關係。建議家長應隨時注意孩童的動作發展狀況，以瞭解與掌握孩子全方面的發展</p>
<div class="table-responsive text-center">
    <table class="table table-bordered">
        <thead>
            <th>評測能力</th>
            <th colspan="{{ \App\Model\AmtAlsRpt::TOTAL_DEEP_STEP - 1 }}">評測項目</th>
            <th>實際能力等級</th>
            <th>同齡能力等級</th>
        </thead>
        <tbody>
            @include('backend/amt_als_rpt/show/report/component/tdRec', [
                'childCategory' => \App\Model\AmtCategory::find(\App\Model\AmtCategory::ID_ROUGH_ACTION), 
                'colSpanCount' => \App\Model\AmtAlsRpt::TOTAL_DEEP_STEP
            ])  
        </tbody>
    </table>
</div>