<h1 class="text-center"><strong>感觉处理</strong></h1>
<blockquote>
    <p class="lead">意即孩童对于环境刺激感觉讯息的接收程度与行为处理模式，感觉阈值即是孩童的神经系统对于刺激的界线，界线过高而行为消极的孩童易出现精神涣散、专注力不集中；界线过高而行为消极的孩童则是过度亢奋、静不下来；界线过低的孩子的神经系统一直处于备战状态，会出现排斥状况甚至出现攻击行为。</p>
</blockquote>
<br/>
<div class="table-responsive text-center">
    <h2>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="background-color:#FF8181">象限</td>
                    <td class="lead">1.低登陆量<br/>[阈值高-行为被动]</td>
                    <td class="lead">2.感觉需求<br/>[阈值高-行为主动]</td>
                    <td class="lead">3.感觉敏感<br/>[阈值低-行为被动]</td>
                    <td class="lead">4.感觉逃避<br/>[阈值低-行为主动]</td>
                </tr>
                <tr>
                    <td style="background-color:#FF8181">感觉处理评分</td>
                    @foreach ([
                        \App\Model\AlsRptIbCxt::SYMBOL_LAND,
                        \App\Model\AlsRptIbCxt::SYMBOL_SEARCH,
                        \App\Model\AlsRptIbCxt::SYMBOL_SENSITIVE,
                        \App\Model\AlsRptIbCxt::SYMBOL_DODGE
                    ] as $symbol)
                    <?php $quarLevel = array_get($quarLevels, $symbol); ?>
                    <td class="lead">
                        @if (0 === (int) $quarLevel)
                        --<br>比大多数人少很多
                        @elseif (1 === (int) $quarLevel)
                        -<br>比大多数人少
                        @elseif (2 === (int) $quarLevel)
                        =<br>与大多数人类似
                        @elseif (3 === (int) $quarLevel)
                        +<br/>比大多数人多
                        @elseif (4 === (int) $quarLevel)
                        ++<br/>比大多数人多很多
                        @endif
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </h2>
</div>