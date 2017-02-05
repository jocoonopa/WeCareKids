<h1 class="text-center">
    <strong>{{$child->name}}的天赋能力培育计划</strong>
</h1>
<div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td class="lead">重点目标</td>
                <td>
                    @foreach ($complexStats as $key => $complexStat)
                        <?php $strDescs = []; ?>
                        @foreach ($complexStat as $stats)
                            @foreach ($stats as $content => $level)
                                <?php $strDescs[] = $content; ?>
                            @endforeach
                        @endforeach
                        @if('优势能力' === $key and !empty($complexStat))
                            <p class="lead">
                                优势能力:&nbsp;&nbsp;<strong>{{ implode($strDescs, ',') }}</strong>
                            </p>
                        @endif
                        @if('弱势能力' === $key and !empty($complexStat))
                            <p class="lead">
                                弱势能力:&nbsp;&nbsp;<strong>{{ implode($strDescs, ',') }}</strong>
                            </p>
                        @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="lead">教学计划</td>
                <td>
                    @foreach ($complexStats as $key => $complexStat)
                        <?php $strDescs = []; ?>
                        @foreach ($complexStat as $stats)
                            @foreach ($stats as $content => $level)
                                <?php $strDescs[] = $content; ?>
                            @endforeach
                        @endforeach
                        @if('优势能力' === $key and !empty($complexStat))
                            @foreach ($strDescs as $str)
                                <p class="lead">{!! \AmtAlsRpt::getSuggestion($str, $defaultLevel, true) !!}</p>
                            @endforeach
                        @endif

                        @if('弱势能力' === $key and !empty($complexStat))
                            @foreach ($strDescs as $str)
                                <p class="lead">{!! \AmtAlsRpt::getSuggestion($str, $defaultLevel, false) !!}</p>
                            @endforeach
                        @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="lead">建议课程</td>
                <td>
                    @if ($courses->isEmpty())
                        <button class="btn btn-lg btn-warning">{{'目前无建议课程'}}</button>
                    @else
                        @foreach ($courses as $course)
                            @unless (5 === $course->id)
                                <a href="/backend/courses/{{$course->id}}" class="btn btn-lg btn-success" target="_blank">{{$course->name}}</a>
                            @else
                                <button class="btn btn-lg btn-success">{{$course->name}}</button>
                            @endunless
                            
                        @endforeach 
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
<br/>
<h4 class="text-right">
    <strong>报告负责人： {{$report->owner->name}}</strong>
</h4>
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        <div class="text-center">
            @include('component/qrcode', [
                'size' => 250,
                'string' => URL::to("/frontend/amt_als_rpt/{$report->id}")
            ])
        </div>
    </div>
</div>
<div class="text-center">
    <a href="{{"/frontend/amt_als_rpt/{$report->id}"}}">{{ url("/frontend/amt_als_rpt/{$report->id}") }}</a>
</div>