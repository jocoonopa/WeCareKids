<div class="table-responsive">
<h2>
<table class="table table-bordered table-hover text-center">
    <thead style="background-color:#F5F5F5;color:#424242;">
        <tr>
            <th class="lead text-center">能力评比</th>
            <th class="lead text-center" style="width: 350px;">整体建议</th>
            <th class="lead text-center">天赋能力</th>
            <th class="lead text-center">Level</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($complexStats as $key => $complexStat)
            <tr>
                @if($loop->first)
                    <td class="lead amt-compare-td amt-greater-td" rowspan="{{count($complexStat) + 1}}">
                @elseif(1 === $loop->index)
                    <td class="lead amt-compare-td amt-equal-td" rowspan="{{count($complexStat) + 1}}">
                @else
                    <td class="lead amt-compare-td amt-worse-td" rowspan="{{count($complexStat) + 1}}">
                @endif
                    <strong>{{$key}}</strong>
                </td>
                <td class="lead" rowspan="{{count($complexStat) + 1}}">
                    <?php $strDescs = []; ?>
                    @foreach ($complexStat as $stats)
                        @foreach ($stats as $content => $level)
                            <?php $strDescs[] = $content; ?>
                        @endforeach
                    @endforeach
                    @if('优势能力' === $key)
                        孩子的<strong>{{ implode($strDescs, ',') }}</strong>，在测验中表现良好，可做为重点培育能力。
                    @endif

                    @if('符合标准' === $key)
                        孩子的<strong>{{ implode($strDescs, ',') }}</strong>在测验过程中表现一般，若从生活中多给予练习，可提升整体能力发展。
                    @endif

                    @if('弱势能力' === $key)
                        孩子的<strong>{{ implode($strDescs, ',') }}</strong>，在测验过程中表现稍落后，建议及早补强，以避免影响学习效率。
                    @endif

                </td>
            </tr>
            @foreach ($complexStat as $stats)
                @foreach ($stats as $content => $level)
                <tr>
                    <td class="lead">{{ $content }}</td>
                    <td class="lead">{{ $level }}</td>
                </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
</h2>
</div>