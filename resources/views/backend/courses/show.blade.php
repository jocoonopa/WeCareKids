@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">

@if (1 == $id)
<div class="row">
    <div class="col-md-12">
        <img style="padding-top:50px;height:400px" class="center-block" src="/images/backend/course/c{{$id}}.png">

        <h1 class="text-center">
            大脑感觉课程
        </h1>
        <h4 class="text-center">适合0-8岁孩子，以Level分班</h4>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3>课程内容：</h3>
        </br>
        <span>给孩子大脑所需求的感觉刺激，促进大脑整合多元感官讯息，让身体做出最有效率的动作，全面提升孩子的动作发展、社交情绪，进而培养出更强的自信心与自尊心，让孩子有效掌握自我的天生优势力。           
        </span>
        </br>
        </br>
    </div>
</div>
<div class="row">
    <div class="col-md-6  col-md-offset-3">
        <h3>解决生活与学习问题：</h3>
    </div>

    <div class="col-md-3 col-md-offset-3">
        <ul>
            <li>上课喜欢乱动</li>
            <li>注意力不集中</li>
            <li>个性过于冲动或胆小</li>
            <li>学习时易受到挫折</li>
            <li>大脑思考速度慢</li>
        </ul>
    </div>    
    
    <div class="col-md-3">
        <ul>
            <li>个学科学习效率低落</li>
            <li>东西常常落东洛西</li>
            <li>走路或跑步常跌倒</li>
            <li>常抄错题、漏抄题</li>
            <li>书写时字写颠倒</li>
        </ul>
    </div>
</div>
@endif

@if (2 == $id)
<div class="row">
    <img style="padding-top:50px;height:400px" class="center-block" src="/images/backend/course/c{{$id}}.png">

    <h1 class="text-center">肌肉控制课程</h1>

    <h4 class="text-center">适合1-12岁孩子，以Level分班</h4>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3>课程内容：</h3>

        </br>
        <span>            
            肌肉控制能力是孩子所有动作的基础，包括：坐、站、蹲、弯腰与平衡等生活中必须使用的能力。课程中利用游戏提供孩子身体躯干、手、脚的肌肉力量与拮抗肌群的控制能力，让孩子的学习
            更专注、更持久、更自信。            
        </span>
        </br></br>
    </div>
</div>
<div class="row">
    <div class="col-md-6  col-md-offset-3">
        <h3>解决生活与学习问题：</h3>
    </div>
    <div class="col-md-3 col-md-offset-3">
        <ul>
            <li>站无站姿、坐无坐相</li>
            <li>怕黑、缺乏安全感</li>
            <li>心情容易紧张、焦虑</li>
            <li>方向感与空间感差</li>
            <li>经常跌倒、动作技巧差</li>
        </ul>
    </div>
    <div class="col-md-3">
        <ul>
            <li>学习时易感到疲累</li>
            <li>喜欢趴在桌上写字</li>
            <li>四肢肌肉力量不足</li>
            <li>各类运动技巧不佳</li>
            <li>容易过度兴奋</li>
        </ul>
    </div>
</div>
@endif

@if (3 == $id)
    <div class="row">
        <img style="padding-top:50px;height:400px" class="center-block" src="/images/backend/course/c{{$id}}.png">
    
        <h1 class="text-center"><big>
        动作灵敏课程</big>
        </h1>
        <h4 class="text-center">适合3-12岁孩子，以level分班</h4>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h3>课程内容：</h3>
            </br>
            <span>
                包含「移位能力」与「物体操作」二类，也就是大家常说的「协调能力」。课程中训练跑、跳、丢接球、踢球等能力，强化孩子全身肌肉、骨骼、关节间的配合，让大脑能够学习更快速地组织身体动作的技巧，使学习事半功倍。   
            </span>
            </br></br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6  col-md-offset-3">
            <h3>
            解决生活与学习问题：
            </h3>
        </div>

        <div class="col-md-3 col-md-offset-3">
            <ul>
                <li>行为举止笨拙</li>
                <li>体能与肌耐力差</li>
                <li>身体平衡感差</li>
                <li>节奏与韵律感差</li>
                <li>手、眼、脚动作不协调</li>
            </ul>
        </div>
        <div class="col-md-3 ">
            <ul>
                <li>写字速度慢</li>
                <li>做事情效率低</li>
                <li>对事物理解速度慢</li>
                <li>自我信心不足</li>
                <li>各类运动学习力低</li>
            </ul>
        </div>
    </div>
@endif

@if (4 == $id)
<div class="row">
    <img style="padding-top:50px;height:400px" class="center-block" src="/images/backend/course/c{{$id}}.png">

    <h1 class="text-center">高阶计划课程</h1>

    <h4 class="text-center">适合5-12岁孩子，Level分班</h4>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h3>课程内容：</h3>
        </br>
        <span>                
            针对希望学习能力领先超群的孩子，对孩子的挑战较高。<br>
            课程融入多元感官处理、精确的操作表现、多向度专注力，让孩子的大脑在同一时间接收多组讯息，立即组织并作出反应，使
            孩子高效学习，发扬天赋。                
        </span>
        </br></br>
    </div>
</div>
<div class="row">
    <div class="col-md-6  col-md-offset-3">
        <h3>解决生活与学习问题：</h3>
    </div>

    <div class="col-md-3 col-md-offset-3">
        <ul>
            <li>培养高效率学习能力</li>
            <li>强化事物组组织能力</li>
            <li>提升抽象认知概念</li>
            <li>强化逻辑思考力</li>
            <li>强化记忆力广度</li>
        </ul>
    </div>
    <div class="col-md-3">
        <ul>
            <li>专注力转换稳定快速</li>
            <li>培养高社交技巧</li>
            <li>提升环境适应力</li>
            <li>做事执行力更强</li>
            <li>拥有高阶运动技能
            </li>
        </ul>
    </div>
</div>
@endif

<div class="clearfix" style="padding-bottom: 50px;"></div>

</div>
@endsection
