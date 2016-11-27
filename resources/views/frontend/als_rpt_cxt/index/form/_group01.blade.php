<table class="table table-bordered table-hover table-condensed">
    <thead style="background-color: #2ecc71; color:#fff;">
        <tr>
            <th colspan="2">
                <strong>项 目</strong>
            </th>
            <th><strong>一、味觉/嗅觉处理</strong></th>
            <th><strong>選項</strong></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="symbol04">｜</td>
            <td>1</td>
            <td>在商店里闻到强烈的气味时(如：肥皂、沐浴乳、洗发精或香水的气味)，孩子会走开。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '0_0'])
            </td>
        </tr>
        <tr>
            <td class="symbol02">§</td>
            <td>2</td>
            <td>孩子会加一些味道比较重的调味料到食物里(如：葱、辣椒或胡椒粉)。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '0_1'])
            </td>
        </tr>
        <tr>
            <td class="symbol01">—</td>
            <td>3</td>
            <td>当别人说他闻到某些气味时，孩子却没有闻到。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '0_2'])
            </td>
        </tr>
        <tr>
            <td class="symbol02">§</td>
            <td>4</td>
            <td>孩子喜欢接近擦香水的人。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '0_3'])
            </td>
        </tr>
        <tr>
            <td class="symbol04">｜</td>
            <td>5</td>
            <td>孩子只吃常吃的食物。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '0_4'])
            </td>
        </tr>
        <tr>
            <td class="symbol01">—</td>
            <td>6</td>
            <td>孩子觉得许多食物吃起来很清淡、没甚么味道。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '0_5'])
            </td>
        </tr>
        <tr>
            <td class="symbol03">○</td>
            <td>7</td>
            <td>孩子不喜欢吃酸的、辣的或薄荷口味的糖果。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '0_6'])
            </td>
        </tr>
        <tr>
            <td class="symbol02">§</td>
            <td>8</td>
            <td>当孩子看见鲜花时，会走过去闻它。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '0_7'])
            </td>
        </tr>
    </tbody>
</table>                                
<p class="lead">其他意见或观察：</p>
@include('frontend/als_rpt_cxt/index/form/component/textarea', ['name' => '0_8'])
<br>
<br>