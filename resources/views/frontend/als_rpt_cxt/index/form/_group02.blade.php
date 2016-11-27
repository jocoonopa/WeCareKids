<table class="table table-bordered table-hover table-condensed">
    <thead style="background-color: #f39c12; color:#fff;">
        <tr>
            <th colspan="2"><strong>项 目</strong></th>
            <th><strong>二、动作</strong></th>
            <th><strong>選項</strong></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="symbol03">○</td>
            <td>9</td>
            <td>孩子会怕高。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '1_0'])
            </td>
        </tr>
        <tr>
            <td class="symbol02">§</td>
            <td>10</td>
            <td>孩子喜欢「动」的感觉。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '1_1'])
            </td>
        </tr>
        <tr>
            <td class="symbol04">｜</td>
            <td>11</td>
            <td>孩子不喜欢搭电梯，因为不喜欢那种「移动」的感觉。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '1_2'])
            </td>
        </tr>
        <tr>
            <td class="symbol01">—</td>
            <td>12</td>
            <td>孩子会被东西绊倒或撞到东西。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '1_3'])
            </td>
        </tr>
        <tr>
            <td class="symbol03">○</td>
            <td>13</td>
            <td>不喜欢坐车，不喜欢那种移动的感觉。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '1_4'])
            </td>
        </tr>
        <tr>
            <td class="symbol02">§</td>
            <td>14</td>
            <td>会参加体能活动。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '1_5'])
            </td>
        </tr>
        <tr>
            <td class="symbol01">—</td>
            <td>15</td>
            <td>爬楼梯时常不确定脚的位置，需要一直盯着楼梯爬。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '1_6'])
            </td>
        </tr>
        <tr>
            <td class="symbol03">○</td>
            <td>16</td>
            <td>孩子容易感到头晕。</td>
            <td>
                @include('frontend/als_rpt_cxt/index/form/component/radio', ['name' => '1_7'])
            </td>
        </tr>
    </tbody>
</table>
<p class="lead">其他意见或观察：</p>
@include('frontend/als_rpt_cxt/index/form/component/textarea', ['name' => '1_8'])
<br>
<br>