<div class="table-responsive">
    <h2>
        <table class="table table-bordered text-center">
            <tbody>
                <tr>
                    @if (!is_null($report->cxtBelongs))
                    <th class="text-center">
                        <img src="/images/backend/als_rpt_ib_cxt/image010.png" class="img-responsive" style="margin: 0 auto;"><br/>
                        <h2><strong>感覺處理</strong></h2>
                    </th>
                    @endif
                    <th class="text-center">
                        <img src="/images/backend/als_rpt_ib_cxt/image011.png" class="img-responsive" style="margin: 0 auto;"><br/>
                        <h2><strong>感覺統合</strong></h2>
                    </th>
                    <th class="text-center">
                        <img src="/images/backend/als_rpt_ib_cxt/image012.png" class="img-responsive" style="margin: 0 auto;"><br/>
                        <h2><strong>粗大動作</strong></h2>
                    </th>
                </tr>
                <tr>
                    @if (!is_null($report->cxtBelongs))
                    <td class="text-danger">
                        低登陸量<br/>孩子在感覺處理中處於<br/>[閾值高]-[被動]
                    </td>
                    @endif
                    <td>Level {{ $iLevel}}</td>
                    <td>Level {{ $eLevel}}</td>
                </tr>
                <tr>
                    @if (!is_null($report->cxtBelongs))
                    <td class="text-danger">敘述低登陸量</td>
                    @endif
                    <td class="text-danger">敘述表現一般</td>
                    <td class="text-danger">敘述表現一般</td>
                </tr>
            </tbody>
        </table>
    </h2>
</div>