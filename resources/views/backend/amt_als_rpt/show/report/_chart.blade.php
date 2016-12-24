<div class="row">
    <div class="col-md-offset-3 col-md-6 col-sm-offset-2 col-sm-8 col-xs-12">
        <canvas id="myDoughnutChart" width="250" height="250"></canvas>
    </div>
    <div class="col-md-3 col-sm-0 col-xs-0"></div>
</div>
<br/>
<br/>
<h2>
    <strong>您的孩子个体内的智能运动天赋优势是：</strong>
</h2>

<h1 class="text-center" style="color:#f0ad4e;">
    @foreach ($complexStats as $key => $complexStat)
        <p class="lead">
            <?php $strDescs = []; ?>
            @foreach ($complexStat as $stats)
                @foreach ($stats as $content => $level)
                    <?php $strDescs[] = $content; ?>
                @endforeach
            @endforeach
            
            @if('优势能力' === $key)
                <strong>{{ implode($strDescs, ',') }}</strong>
            @endif
        </p>
    @endforeach
</h1>

<p class="lead">整体来说，孩子在大脑思维优于其他能力，建议在个性化培育时，以优势带动弱势设计培育计划，若朝此方向培育，将对大脑功能、思维速度、具体抽象转换、学习能力等有效率的增长，未来在学习上将有较大的成就。</p>
<p class="lead">优尼尔建议，非主要天赋倾向的能力，亦需要接受足够的培育，以达“天赋平衡”的状态，才可让主要天赋更加大放异彩。</p>

<h1 class="text-center">
    <span class="label label-primary">二、测评等级</span>
</h1>

<div class="row">
    <div class="col-md-3 col-sm-0 col-xs-0"></div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <canvas id="myBarChart" width="250" height="250"></canvas>
    </div>
    <div class="col-md-3 col-sm-0 col-xs-0"></div>
</div>
<br/>