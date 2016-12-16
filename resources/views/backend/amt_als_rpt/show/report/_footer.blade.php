<h1 class="text-center">
    <strong>{{$child->name}}的天赋能力培育计划</strong>
</h1>
<div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>重点目标</td>
                <td>
                     @foreach ($complexStats as $key => $complexStat)
                        <?php $strDescs = []; ?>
                        @foreach ($complexStat as $stats)
                            @foreach ($stats as $content => $level)
                                <?php $strDescs[] = $content; ?>
                            @endforeach
                        @endforeach
                        @if('优势能力' === $key and !empty($complexStat))
                            <p>
                                优势能力:&nbsp;&nbsp;<strong>{{ implode($strDescs, ',') }}</strong>
                            </p>
                        @endif

                        @if('弱势能力' === $key and !empty($complexStat))
                            <p>
                                弱势能力:&nbsp;&nbsp;<strong>{{ implode($strDescs, ',') }}</strong>
                            </p>
                        @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>教学计划</td>
                <td>
                    
                </td>
            </tr>
            <tr>
                <td>建议课程</td>
                <td>
                    @foreach ($courses as $course)
                        <span class="label label-success">{{$course->name}}</span>
                    @endforeach 
                </td>
            </tr>
        </tbody>
    </table>
</div>
<br/>
<h4 class="text-right">
    <strong>报告负责人： {{$report->owner->name}}</strong>
</h4>