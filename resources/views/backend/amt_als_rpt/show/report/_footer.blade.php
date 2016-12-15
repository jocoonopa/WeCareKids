<h1 class="text-center">
    <strong>{{$child->name}}的天賦能力培育計畫</strong>
</h1>
<div class="table-responsive">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td class="text-center">重點目標</td>
                <td>
                    @if ($iLevel > $eLevel)
                    <p>優勢能力:  感覺統合</p> 
                    @else 
                    <p>優勢能力:  粗大動作 </p>
                    @endif 

                    @if ($iLevel < $eLevel)
                    <p>弱勢能力:  感覺統合</p>
                    @else
                    <p>弱勢能力:  粗大動作</p>
                    @endif
                    
                    @if ($iLevel === $eLevel)
                    <p>持續維持</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td>教學計劃</td>
                <td>
                    <h4>感覺統合:</h4>
                    <p>
                        @if ($iLevel > $defaultLevel)
                        發展年齡超前實際年齡6個月以上，{{ $organiztion->name }}會針對{{$child->name}}高潛能的感覺統合發展，給予更豐富的感覺刺激活動，強化大腦整合訊息，提升行為適應性與反應能力，以培育為優勢能力。
                        @endif

                        @if ($iLevel == $defaultLevel)
                        發展年齡與實際年齡落差小於6個月，{{ $organiztion->name }}會針對{{$child->name}}的感覺統合發展，持續給予適切挑戰性的感覺刺激活動，以維持大腦整合訊息之能力，提升孩子學習效率，培育感覺統合的整體發展。

                        @endif

                        @if ($iLevel < $defaultLevel)
                        發展年齡落後實際年齡6個月以上，{{ $organiztion->name }}會分析{{$child->name}}在感覺系統的弱勢項目，漸進式增加相關的感覺刺激活動，補強大腦整合訊息之能力，全面性提升孩子對環境適應與探索的能力，增進學習效率。
                        @endif
                    </p>
                    

                    <h4>粗大動作:</h4>
                    <p>
                        @if ($eLevel > $defaultLevel)
                        發展年齡超前實際年齡6個月以上，{{ $organiztion->name }}會針對{{$child->name}}高潛能的感覺統合發展，給予更豐富的感覺刺激活動，強化大腦整合訊息，提升行為適應性與反應能力，以培育為優勢能力。
                        @endif

                        @if ($eLevel == $defaultLevel)
                        發展年齡與實際年齡落差小於6個月，{{ $organiztion->name }}會針對{{$child->name}}的感覺統合發展，持續給予適切挑戰性的感覺刺激活動，以維持大腦整合訊息之能力，提升孩子學習效率，培育感覺統合的整體發展。

                        @endif

                        @if ($eLevel < $defaultLevel)
                        發展年齡落後實際年齡6個月以上，{{ $organiztion->name }}會分析{{$child->name}}在感覺系統的弱勢項目，漸進式增加相關的感覺刺激活動，補強大腦整合訊息之能力，全面性提升孩子對環境適應與探索的能力，增進學習效率。
                        @endif
                    </p>
                </td>
            </tr>
            <tr>
                <td>建議課程</td>
                <td class="text-danger">NotYet</td>
            </tr>
        </tbody>
    </table>
</div>
<br/>
<h4 class="text-right">
    <strong>報告負責人：職能治療師 {{$report->owner->name}}</strong>
</h4>