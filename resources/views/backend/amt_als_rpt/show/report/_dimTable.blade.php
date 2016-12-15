<div class="table-responsive">
<h2>
<table class="table table-bordered table-hover text-center">
    <thead style="background-color:#F5F5F5;color:#424242;">
        <tr>
            <th class="text-center">能力評比</th>
            <th class="text-center" style="width: 350px;">整體建議</th>
            <th class="text-center">天賦能力</th>
            <th class="text-center">Level</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($complexStats as $key => $complexStat)
            <tr>
                @if($loop->first)
                    <td class="amt-compare-td amt-greater-td" rowspan="{{count($complexStat) + 1}}">
                @elseif(1 === $loop->index)
                    <td class="amt-compare-td amt-equal-td" rowspan="{{count($complexStat) + 1}}">
                @else
                    <td class="amt-compare-td amt-worse-td" rowspan="{{count($complexStat) + 1}}">
                @endif
                    <strong>{{$key}}</strong>
                </td>
                <td rowspan="{{count($complexStat) + 1}}">
                    <?php $strDescs = []; ?>
                    @foreach ($complexStat as $stats)
                        @foreach ($stats as $content => $level)
                            <?php $strDescs[] = $content; ?>
                        @endforeach
                    @endforeach
                    @if('優勢能力' === $key)
                        孩子的<strong>{{ implode($strDescs, ',') }}</strong>，在測驗中表現良好，可做為重點培育能力。
                    @endif

                    @if('符合標準' === $key)
                        孩子的<strong>{{ implode($strDescs, ',') }}</strong>在測驗過程中表現一般，若從生活中多給予練習，可提升整體能力發展。
                    @endif

                    @if('弱勢能力' === $key)
                        孩子的<strong>{{ implode($strDescs, ',') }}</strong>，在測驗過程中表現稍落後，建議及早補強，以避免影響學習效率。
                    @endif

                </td>
            </tr>
            @foreach ($complexStat as $stats)
                @foreach ($stats as $content => $level)
                <tr>
                    <td>{{ $content }}</td>
                    <td>{{ $level }}</td>
                </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
</h2>
</div>