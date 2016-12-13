<!-- #################### End ####################  -->
<div class="table-responsive text-center">
    <table class="table table-bordered">
        <thead>
            <tr class="text-center" style="background-color:#FFB9B9">
                <th><p>六大感覺</p></th>
                <th>
                    1.低登陸量<p>[閾值高-行為被動]</p>
                </th>
                <th>
                    2.感覺需求<p>[閾值高-行為主動]</p>
                </th>
                <th>
                    3.感覺敏感<p>[閾值低-行為被動]</p>
                </th>
                <th>
                    4.感覺逃避<p>[閾值低-行為主動]</p>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alsData as $sense => $value)
            <tr>
                <td>{{ \App\Model\AlsRptIbCxt::$senseDesc[$sense]}}</td>
                @foreach (\App\Model\AlsRptIbCxt::$senseStandards[$sense] as $key => $standard)
                <td class="als-table-symbol">
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