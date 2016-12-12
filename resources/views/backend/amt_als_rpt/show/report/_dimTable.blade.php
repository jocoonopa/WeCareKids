<div class="table-responsive">
<h2>
<table class="table table-bordered table-hover text-center">
    <thead style="background-color:#F5F5F5;color:#424242;">
        <tr>
            <th class="text-center">能力評比</th>
            <th class="text-center">整體建議</th>
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
                <td rowspan="{{count($complexStat) + 1}}">系統提供的建議</td>
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