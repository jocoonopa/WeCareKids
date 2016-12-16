<?php $ranges = \App\Model\Child::getYearMonthRangeFromMap($avgLevel); ?>
<h1 class="text-center" style="color:#f0ad4e;">
    <strong>智能运动能力等级：Level {{ $avgLevel}}</strong>
</h1>

@if ((int) $avgLevel > (int) $defaultLevel)
<?php $theDesc = '超前6个月：相较下发展潜力高，应增加给予相关活动，以培育为优势能力。' ?>
@endif

@if ((int) $avgLevel === (int) $defaultLevel)
<?php $theDesc = '落差小于6个月：相符合，应持续给予相关活动，以维持智能运动的整体发展。' ?>
@endif

@if ((int) $avgLevel < (int) $defaultLevel)
<?php $theDesc = '稍慢6个月：相较下发展落后，应积极给予相关活动，及早补强，避免影响学习效率。' ?>
@endif

<p class="lead">智能运动为孩子的感觉统合与粗大动作之发展状况，从孩子大脑整合与肢体协调的了解孩子在体能方面的发展潜能。</p>
<p class="lead">您的孩子在智能运动项目为<strong>Level {{$avgLevel}}</strong>，发展年龄为
    <strong>{{ array_get($ranges, 0) . '~' . array_get($ranges, 1) }}</strong>
    ，与您的实际年龄相比，发展年龄与实际年龄{{$theDesc}}</p>
<br/>

{{-- 感觉统合 --}}
<h1 class="text-center">
    <strong>感觉统合</strong>
</h1>
<p class="lead">感觉统合是大脑神经系统组织、诠释周遭环境的感觉讯息，让我们对环境刺激有正确的认识后，才能对外界做适当的互动和学习。若我们对环境的讯息接收不当，易出现感觉调节困难、专注力不集中、学习困难等状况。</p>
<div class="text-center">
    <table class="table table-bordered">
        <thead>
            <th>评测能力</th>
            <th colspan="{{ \App\Model\AmtAlsRpt::TOTAL_DEEP_STEP - 1 }}">评测项目</th>
            <th>实际能力等级</th>
            <th>同龄能力等级</th>
        </thead>
        <tbody>
            @include('backend/amt_als_rpt/show/report/component/tdRec', [
                'childCategory' => \App\Model\AmtCategory::find(\App\Model\AmtCategory::ID_FEEL_INTEGRATE), 
                'colSpanCount' => \App\Model\AmtAlsRpt::TOTAL_DEEP_STEP
            ])  
        </tbody>
    </table>
</div>

{{-- 粗大动作 --}}
<h1 class="text-center">
    <strong>粗大动作</strong>
</h1>
<p class="lead">动作发展是指孩子的神经与肌肉骨骼系统会随着年龄的增加，出现简单至复杂的大肌肉活动，如：婴幼儿翻身至成人踢球。每个年龄皆有黄金动作发展里程碑，而动作发展的表现不单是肌肉骨骼的成熟，还包括感觉系统整合、心肺功能、心理认知发展等多元系统的互动关系。建议家长应随时注意孩童的动作发展状况，以了解与掌握孩子全方面的发展</p>
<div class="text-center">
    <table class="table table-bordered">
        <thead>
            <th class="lead">评测能力</th>
            <th class="lead" colspan="{{ \App\Model\AmtAlsRpt::TOTAL_DEEP_STEP - 1 }}">评测项目</th>
            <th class="lead">实际能力等级</th>
            <th class="lead">同龄能力等级</th>
        </thead>
        <tbody>
            @include('backend/amt_als_rpt/show/report/component/tdRec', [
                'childCategory' => \App\Model\AmtCategory::find(\App\Model\AmtCategory::ID_ROUGH_ACTION), 
                'colSpanCount' => \App\Model\AmtAlsRpt::TOTAL_DEEP_STEP
            ])  
        </tbody>
    </table>
</div>