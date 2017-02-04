<h1>
    <strong>优尼尔</strong>
    <small>测评日期: {{ Carbon\Carbon::now()->format('Y-m-d') }}  </small>
    <small>
        <span class="label {{\Wck::getCxtStatusLabel($cxt)}}">
            {{ $cxt->getStatusDesc() }}
        </span>
    </small>
</h1>

<div class="form-group">
    <label for="child_name">子女姓名</label>
    <input type="text" id="child_name" name="child_name" class="form-control" value="{{ $cxt->child_name}}" required>
</div>

<div class="form-group">
    <label for="child_sex">子女性别</label>
    <select id="child_sex" name="child_sex" class="form-control">
        @foreach (['女', '男'] as $key => $sex)
            <option value="{{ $key }}" @if($key == $cxt->child_sex) selected @endif>{{$sex}}</option>
        @endforeach
    </select>       
</div>

<div class="form-group">
    <label for="child_birthday">子女出生年月日<small id="child_age" class="lead text-center"></small></label>
    {!! Form::date('child_birthday', $cxt->child_birthday, [
        'id' => 'child_birthday', 
        'class' => 'form-control', 
        'required' => true
    ]) !!}
</div>

<div class="form-group">
    <label>截止时间</label>
    <input type="text" class="form-control" readonly value="{{ $cxt->channel->close_at->format('Y-m-d') }}">
</div>

<div class="form-group">
    <label for="school_name">子女就读学校</label>
    <input type="text" name="school_name" class="form-control" id="school_name" value="{{ $cxt->school_name }}">
</div>

<div class="form-group">
    <label for="grade_num">子女就读年级</label>
    <input type="number" name="grade_num" class="form-control" id="grade_num" value="{{ $cxt->grade_num }}">
</div>

<div class="form-group">
    <label for="filler_name">填写人姓名</label>
    <input type="text" name="filler_name" class="form-control" id="filler_name" value="{{ $cxt->filler_name }}" required>
</div>

<div class="form-group">
    <label for="filler_sex">填写人性别</label>
    <select id="filler_sex" name="filler_sex" class="form-control">
        @foreach (['女', '男'] as $key => $sex)
            <option value="{{ $key }}" @if($key == $cxt->filler_sex) selected @endif>{{$sex}}</option>
        @endforeach
    </select>   
</div>

<div class="form-group">
    <label for="relation">与填写人关系</label>
    <input type="text" name="relation" class="form-control" id="relation" value="{{ $cxt->relation }}">
</div>

<div class="form-group">
    <label for="phone">连络电话</label>
    <input type="number" name="phone" class="form-control" id="phone" value="{{ $cxt->phone }}" required>
</div>

<div class="form-group">
    <label for="email">连络信箱</label>
    <input type="email" name="email" class="form-control" id="email" value="{{ $cxt->email }}">
</div>
<h2><strong>填表说明</strong></h2>
<p class="lead">本量表一共60题，请根据孩子的日常表现客观勾选，并在「其他意见或观察」栏填写额外的补充、说明。</p>
                                        
<p class="lead">各反应频率界定如下：</p>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center"><strong>项目</strong></th>
            <th class="text-center"><strong>说明</strong></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>几乎从不</td>
            <td>题目所描述的情况，孩童<b>几乎从来不会出现</b>此反应，即从来未发生过，频率低于5%。</td>
        </tr>
         <tr>
            <td>很    少</td>
             <td>题目所描述的情况，孩童只有在<b>某些时候</b>才会出现此反应，频率低于25%。</td>
        </tr>
         <tr>
            <td>有    时</td>
            <td>题目所描述的情况，孩童出现此反应的频率大约50%。</td>
        </tr>
         <tr>
            <td>经    常</td>
            <td>题目所描述的情况，孩童<b>时常会</b>出现此反应，出现频率大约75%。</td>
        </tr>
         <tr>
            <td>几乎总是</td>
             <td>题目所描述的情况，孩童<b>几乎必定</b>出现此反应，出现频率大约95%。</td>
        </tr>
    </tbody>
</table> 
<br>
<br>
                                        
<p class="lead">感觉处理能力测评问卷可以帮助我们真实地了解日常生活中孩子处理多样感觉刺激信息的客观能力。根据家长所填写的这些信息，我们可以对孩子处理感觉刺激信息的客观能力进行快速、全面地分析。请家长根据平时观察客观填答，填答过程中若有疑问可咨询现场有关负责人员。</p>
<br>
<h4 class="text-right"><strong> </strong></h4> 
