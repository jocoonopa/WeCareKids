@extends('layouts.blank')

@push('stylesheets')
<style>

.analyze-border {
    border-style: solid;
    border-color: #000;
    border-width: 1px;
}
.analyze-border td {
    text-align: center;
    border: none !important;
}

</style>
@endpush

@section('main_container')
<!-- page content -->
<div class="right_col" role="main">
    <!-- ####### Start of Result Tab :Home ##########-->
    <div role="tabpanel" class="tab-pane active" id="home">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel" style="Color:#424242;">
                    <div class="text-center">
                        <h1>Child Name</h1>
                        <p>的天賦能力測評結果</p>
                        <ul class="list-inline">
                            <li>施測日期：</li>
                            <li>出生日期：</li>
                            <li>實足年齡：</li>
                        </ul>
                        <br/>
                        <h1><span class="label label-primary">一、个体天赋能力分布</span></h1>
                        <br/>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-0 col-xs-0"></div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <canvas id="myDoughnutChart" width="250" height="250"></canvas>
                        </div>
                        <div class="col-md-3 col-sm-0 col-xs-0"></div>
                    </div>
                    <br/>
                    <br/>
                    <h2><strong>您的孩子的智能运动天賦优势是：</strong></h2>
                    <h1 class="text-center" style="color:#f0ad4e;"><strong>智能运动倾向</strong></h1>
                    <p class="lead">整體來說，孩子在大腦思維優於其他能力，建議在個性化培育時，以優勢帶動弱勢設計培育計畫，若朝此方向培育，將對大腦功能、思維速度、具體抽象轉換、學習能力等有效率的增長，未來在學習上將有較大的成就。<br/><br/>
                        優尼爾建議，非主要天賦傾向的能力，亦需要接受足夠的培育，以達「天賦平衡」的狀態，才可讓主要天賦更加大放異彩。
                    </p>
                    <h1 class="text-center"><span class="label label-primary">二、测评等级</span></h1>
                    <div class="row">
                        <div class="col-md-3 col-sm-0 col-xs-0"></div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <canvas id="myBarChart" width="250" height="250"></canvas>
                        </div>
                        <div class="col-md-3 col-sm-0 col-xs-0"></div>
                    </div>
                    <br/>
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
                                    <tr>
                                        <td style="background-color:#ef9a9a; color:#424242;"><strong>優勢能力</strong></td>
                                        <td></td>
                                        <td>認知思維</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td rowspan=6 style="background-color:#FFCC80;color:#424242;vertical-align: middle;"><strong>符合標準</strong></td>
                                        <td rowspan=6></td>
                                        <td>精細動作</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>專注力</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>社交情緒</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>語言能力</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>感覺統合</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>粗大動作</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#81D4FA;color:#424242;"><strong>弱勢能力</strong></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </h2>
                    </div>
                    <h1 class="text-center"><span class="label label-primary">三、測評解讀方法</span></h1>
                    <p class="lead">優尼爾的測評標準依據國際兒童發展里程碑為基礎，重新整合測評項目，搜集數千筆大中華區兒童發展資料，完成一套能夠將兒童天賦能力分級的系統「兒童天賦能力等級測評」，説明家長、老師、專業人員以科學化的方式，更深入地瞭解兒童天賦，讓兒童能夠大幅提升學習效率，彌補弱項，優者更優。</p>
                    <h1><strong>一. 實際能力等級-Level</strong></h1>
                    <p class="lead">代表孩子實際能力的評分等級。家長可以與同齡能力等級相互比較，即可知道孩子目前能力的強弱；老師亦可根據此能力等級，判斷孩子在何種等級的課程內，有較大的成效。</p>
                    <h1><strong>二. 同齡能力等級</strong></h1>
                    <p class="lead">能力等級以年齡為基準，如：10個月-1歲孩子的能力常態符合Level 2之能力標準。各等級符合之年齡如下表：</p>
                    <!--####### Level 能力標準 ######-->
                    <div class="row">
                        <div class="col-md-3 col-sm-0 col-xs-0"></div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2>
                                <table class="table table-bordered table-hover text-center">
                                    <thead style="background-color: #f0ad4e; color:#fff;">
                                        <tr>
                                            <th class="text-center"><strong>Level</strong></th>
                                            <th class="text-center"><strong>符合年齡</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>7 个月 － 9 个月</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>10 个月 － 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>1 岁 － 1 岁 5 个月</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>1 岁 6 个月 － 1 岁 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>2 岁 － 2 岁 5 个月</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>2 岁 6 个月 － 2 岁 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>3 岁 － 3 岁 5 个月</td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>3 岁 6 个月 － 3 岁 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>4 岁 － 4 岁 5 个月</td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>4 岁 6 个月 － 4 岁 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>5 岁 － 5 岁 5 个月</td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td>5 岁 6 个月 － 5 岁 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>13</td>
                                            <td>6 岁 － 6 岁 5 个月</td>
                                        </tr>
                                        <tr>
                                            <td>14</td>
                                            <td>6 岁 6 个月 － 6 岁 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>15</td>
                                            <td>7 岁 － 7 岁 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>16</td>
                                            <td>8 岁 － 8 岁 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>17</td>
                                            <td>9 岁 － 9 岁 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>18</td>
                                            <td>10 岁 － 10 岁 11 个月</td>
                                        </tr>
                                        <tr>
                                            <td>19</td>
                                            <td>11 岁 － 11 岁 11 个月</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </h2>
                        </div>
                        <div class="col-md-3 col-sm-0 col-xs-0"></div>
                    </div>
                    <div class="table-responsive">
                        <h2>
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <th class="text-center">
                                            <img src="/images/backend/als_rpt_ib_cxt/image010.png" class="img-responsive" style="margin: 0 auto;"><br/>
                                            <h2><strong>感覺處理</strong></h2>
                                        </th>
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
                                        <td>低登陸量<br/>孩子在感覺處理中處於<br/>[閾值高]-[被動]</td>
                                        <td>Level 2</td>
                                        <td>Level 2</td>
                                    </tr>
                                    <tr>
                                        <td>敘述低登陸量</td>
                                        <td>敘述表現一般</td>
                                        <td>敘述表現一般</td>
                                    </tr>
                                </tbody>
                            </table>
                        </h2>
                    </div>
                    <h1 class="text-center"><strong>感覺處理</strong></h1>
                    <blockquote>
                        <p>意即孩童對於環境刺激感覺訊息的接收程度與行為處理模式，感覺閾值即是孩童的神經系統對於刺激的界線，界線過高而行為消極的孩童易出現精神渙散、專注力不集中；界線過高而行為消極的孩童則是過度亢奮、靜不下來；界線過低的孩子的神經系統一直處於備戰狀態，會出現排斥狀況甚至出現攻擊行為。</p>
                    </blockquote>
                    <br/>
                    <div class="table-responsive text-center">
                        <h2>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td style="background-color:#FF8181">象限</td>
                                        <td>1.低登陸量<br/>[閾值高-行為被動]</td>
                                        <td>2.感覺需求<br/>[閾值高-行為主動]</td>
                                        <td>3.感覺敏感<br/>[閾值低-行為被動]</td>
                                        <td>4.感覺逃避<br/>[閾值低-行為主動]</td>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#FF8181">感覺處理評分</td>
                                        <td>--<br/>比大多數人少很多</td>
                                        <td>++<br/>比大多數人多很多</td>
                                        <td>+<br/>比大多數人多</td>
                                        <td>=<br/>與大多數人類似</td>
                                    </tr>
                                </tbody>
                            </table>
                        </h2>
                    </div>
                    <!-- #################### 感覺處理剖析圖 Begin #################### -->
                    <div class="row">
                        <div class="col-md-offset-2 col-md-4 col-sm-0 col-xs-6">
                            <table class="table analyze-border" style="background-color:#FFCDD2;">
                                <tbody>
                                    <tr>
                                        <!-- #1-->
                                        <td><i class="fa fa-circle-o" fa-2x></i></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #2-->
                                        <td></td>
                                        <td><i class="fa fa-circle-o" fa-2x></i></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #3-->
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" fa-2x></i></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #4-->
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" fa-2x></i></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #5-->
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" fa-2x></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 col-sm-0 col-xs-6">
                            <table class="table analyze-border" style="background-color:#C8E6C9;">
                                <tbody>
                                    <tr>
                                        <!-- #1-->
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                    </tr>
                                    <tr>
                                        <!-- #2-->
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #3-->
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #4-->
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #5-->
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-2 col-md-4 col-sm-0 col-xs-6">
                            <table class="table analyze-border" style="background-color:#D1C4E9;">
                                <tbody>
                                    <tr>
                                        <!-- #1-->
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                    </tr>
                                    <tr>
                                        <!-- #2-->
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #3-->
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #4-->
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #5-->
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 col-sm-0 col-xs-6">
                            <table class="table analyze-border" style="background-color:#BBDEFB;">
                                <tbody>
                                    <tr>
                                        <!-- #1-->
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #2-->
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #3-->
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #4-->
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <!-- #5-->
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-circle-o" aria-hidden="true"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br/>
                    <!-- #################### End ####################  -->
                    <div class="table-responsive text-center">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="background-color:#FFB9B9">
                                    <th class="text-center">六大感覺</th>
                                    <th class="text-center">閥值低</th>
                                    <th class="text-center">閥值正常</th>
                                    <th class="text-center">閥值高</th>
                                    <th class="text-center">行為主動</th>
                                    <th class="text-center">行為正常</th>
                                    <th class="text-center">行為被動</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>味/嗅覺</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>動作</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>視覺</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>觸覺</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>活動量</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>聽覺</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h1 class="text-center" style="color:#f0ad4e;"><strong>智能運動能力等級：Level 8</strong></h1>
                    <p class="lead">智能運動為孩子的感覺統合與粗大動作之發展狀況，從孩子大腦整合與肢體協調的了解孩子在體能方面的發展潛能。
                        <br/> 您的孩子在智能運動項目為Level 8，發展年齡為3y7m-4y0m，與您的實際年齡相比，發展年齡與實際年齡落差小於6個月：能力相符合，應持續給予相關活動，以維持智能運動的整體發展。
                    </p>
                    <br/>
                    <h1 class="text-center"><strong>感觉统合</strong></h1>
                    <p class="lead">感覺統合是大腦神經系統組織、詮釋周遭環境的感覺訊息，讓我們對環境刺激有正確的認識後，才能對外界做適當的互動和學習。若我們對環境的訊息接收不當，易出現感覺調節困難、專注力不集中、學習困難等狀況。</p>
                    <div class="table-responsive text-center">
                        <h2>
                            <table class="table table-bordered">
                                <thead>
                                    <th>評測能力</th>
                                    <th colspan=2>評測項目</th>
                                    <th>實際能力表現</th>
                                    <th>實際能力等級</th>
                                    <th>同齡能力等級</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan=4>視覺</td>
                                        <td colspan=2>惯用侧</td>
                                        <td>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>脸部肌肉</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>追視</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan=2>聚焦</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>聽覺</td>
                                        <td colspan=2>聽覺整合</td>
                                        <td>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>觸覺</td>
                                        <td colspan=2>觸覺整合</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>前庭覺</td>
                                        <td colspan=2>前庭整合</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td rowspan=10>本体觉</td>
                                        <td rowspan=5>肌肉力量</td>
                                        <td>未满一岁六个月</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>背肌肌力</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>腹侧肌力</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>上肢抵抗肌力</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>躯干肌力</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td rowspan=5>雙側協調</td>
                                        <td>未满一岁六个月</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>上臂</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>前臂</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>掌指</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>舌头</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan=3>動作計畫</td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </h2>
                    </div>
                    <h1 class="text-center"><strong>粗大动作</strong></h1>
                    <p class="lead">動作發展是指孩子的神經與肌肉骨骼系統會隨著年齡的增加，出現簡單至複雜的大肌肉活動，如：嬰幼兒翻身至成人踢球。每個年齡皆有黃金動作發展里程碑，而動作發展的表現不單是肌肉骨骼的成熟，還包括感覺系統整合、心肺功能、心理認知發展等多元系統的互動關係。建議家長應隨時注意孩童的動作發展狀況，以瞭解與掌握孩子全方面的發展</p>
                    <div class="table-responsive text-center">
                        <h2>
                            <table class="table table-bordered">
                                <thead>
                                    <th>評測能力</th>
                                    <th>評測項目</th>
                                    <th>實際能力表現</th>
                                    <th>實際能力等級</th>
                                    <th>同齡能力等級</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan=5>姿勢控制</td>
                                        <td>坐姿</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>跪姿</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>站立</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>单脚站立</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>双脚脚尖站立</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td rowspan=6>移位能力</td>
                                        <td>转位</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>爬行</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>行走</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>雙腳跳</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>倒退走</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>單腳跳</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td rowspan=4>协调能力</td>
                                        <td>丟球</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>接球</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>踢球</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>拍球</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </h2>
                    </div>
                    <h1 class="text-center"><strong>XXX的天賦能力培育計畫</strong></h1>
                    <div class="table-responsive text-center">
                        <h2>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>重點目標</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>教學計劃</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>建議課程</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </h2>
                    </div>
                    <br/>
                    <h4 class="text-right"><strong>報告負責人：職能治療師</strong></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ####### End of Result Tab :Home ##########-->

@endsection

@push('scripts')
<script src="/js/chart.min.js"></script>
<script src="/js/backend/als_rpt_ib_cxt/chart.js"></script>
@endpush