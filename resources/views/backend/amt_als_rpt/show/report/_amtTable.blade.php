<!-- #################### End ####################  -->
<div class="table-responsive text-center">
    <table class="table table-bordered">
        <thead>
            <tr class="text-center" style="background-color:#FFB9B9">
                <th><p class="lead">六大感觉</p></th>
                <th class="lead">
                    1.低登陆量<p>[阈值高-行为被动]</p>
                </th>
                <th class="lead">
                    2.感觉需求<p>[阈值高-行为主动]</p>
                </th>
                <th class="lead">
                    3.感觉敏感<p>[阈值低-行为被动]</p>
                </th>
                <th class="lead">
                    4.感觉逃避<p>[阈值低-行为主动]</p>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alsData as $sense => $value)
            <tr>
                <td class="lead">{{ \App\Model\AlsRptIbCxt::$senseDesc[$sense]}}</td>
                @foreach (\App\Model\AlsRptIbCxt::$senseStandards[$sense] as $key => $standard)
                <td class="lead als-table-symbol">
                    @if ($standard * 0.9 <= $value[$key])
                    +
                    @elseif($standard * 0.2 >= $value[$key])
                    -
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>