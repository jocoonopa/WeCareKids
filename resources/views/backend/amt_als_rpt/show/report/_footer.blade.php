<h1 class="text-center">
    <strong>{{$child->name}}的天赋能力培育计划</strong>
</h1>
<div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td class="text-center">重点目标</td>
                <td>
                    @if ($iLevel > $eLevel)
                    <p>优势能力:  感觉统合</p> 
                    @else 
                    <p>优势能力:  粗大动作 </p>
                    @endif 

                    @if ($iLevel < $eLevel)
                    <p>弱势能力:  感觉统合</p>
                    @else
                    <p>弱势能力:  粗大动作</p>
                    @endif
                    
                    @if ($iLevel === $eLevel)
                    <p>持续维持</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td>教学计划</td>
                <td>
                    <h4>感觉统合:</h4>
                    <p>
                        @if ($iLevel > $defaultLevel)
                        发展年龄超前实际年龄6个月以上，{{ $organiztion->name }}会针对{{$child->name}}高潜能的感觉统合发展，给予更丰富的感觉刺激活动，强化大脑整合讯息，提升行为适应性与反应能力，以培育为优势能力。
                        @endif

                        @if ($iLevel == $defaultLevel)
                        发展年龄与实际年龄落差小于6个月，{{ $organiztion->name }}会针对{{$child->name}}的感觉统合发展，持续给予适切挑战性的感觉刺激活动，以维持大脑整合讯息之能力，提升孩子学习效率，培育感觉统合的整体发展。

                        @endif

                        @if ($iLevel < $defaultLevel)
                        发展年龄落后实际年龄6个月以上，{{ $organiztion->name }}会分析{{$child->name}}在感觉系统的弱势项目，渐进式增加相关的感觉刺激活动，补强大脑整合讯息之能力，全面性提升孩子对环境适应与探索的能力，增进学习效率。
                        @endif
                    </p>
                    

                    <h4>粗大动作:</h4>
                    <p>
                        @if ($eLevel > $defaultLevel)
                        发展年龄超前实际年龄6个月以上，{{ $organiztion->name }}会针对{{$child->name}}高潜能的感觉统合发展，给予更丰富的感觉刺激活动，强化大脑整合讯息，提升行为适应性与反应能力，以培育为优势能力。
                        @endif

                        @if ($eLevel == $defaultLevel)
                        发展年龄与实际年龄落差小于6个月，{{ $organiztion->name }}会针对{{$child->name}}的感觉统合发展，持续给予适切挑战性的感觉刺激活动，以维持大脑整合讯息之能力，提升孩子学习效率，培育感觉统合的整体发展。

                        @endif

                        @if ($eLevel < $defaultLevel)
                        发展年龄落后实际年龄6个月以上，{{ $organiztion->name }}会分析{{$child->name}}在感觉系统的弱势项目，渐进式增加相关的感觉刺激活动，补强大脑整合讯息之能力，全面性提升孩子对环境适应与探索的能力，增进学习效率。
                        @endif
                    </p>
                </td>
            </tr>
            <tr>
                <td>建议课程</td>
                <td class="text-danger">NotYet</td>
            </tr>
        </tbody>
    </table>
</div>
<br/>
<h4 class="text-right">
    <strong>报告负责人：职能治疗师 {{$report->owner->name}}</strong>
</h4>