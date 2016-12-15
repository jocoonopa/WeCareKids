 <h1>
    <strong>優尼爾</strong>
</h1>

<p>感觉处理能力分析量表</p>
<h2><strong>基本数据</strong></h2> </div>
<!-- #######################################################################################-->
<table class="table table-bordered table-hover">
    <thead style="background-color: #3498db; color:#eaeaea;">
        <tr>
            <th class="text-center"><strong>子女姓名</strong></th>
            <th class="text-center"><strong>性别</strong></th>
            <th class="text-center"><strong>测评日期</strong></th>
            <th class="text-center"><strong>出生年月日</strong></th>
            <th class="text-center"><strong>实际年龄</strong></th>
            <th class="text-centr"><strong>截止時間</strong></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <input type="text" name="child_name" class="form-control" id="child_name" value="{{ $cxt->child_name}}">
            </td>
            <td>
                <select id="child_sex" name="child_sex" class="form-control">
                    @foreach (['女', '男'] as $key => $sex)
                        <option value="{{ $key }}" @if($key == $cxt->child_sex) selected @endif>{{$sex}}</option>
                    @endforeach
                </select>                
            </td>
            <td>
                <div class="label label-info">
                    {{ Carbon\Carbon::now() }}  
                </div>
            </td>
            <td>
                <input type="text" name="child_birthday" data-birthday="{{ $cxt->child_birthday->format('Y-m-d') }}" class="form-control" id="child_birthday" value="{{ $cxt->child_birthday->format('Y-m-d') }}">
            </td>
            <td id="child_age" class="text-center" style="font-size: 14px;">
            </td>
            <td>
                <div class="label label-danger">{{ $cxt->channel->close_at }}</div>
            </td>
        </tr>
        <tr style="background-color: #3498db; color:#eaeaea;">
            <th class="text-center"><strong>就读学校</strong></th>
            <th class="text-center"><strong>就读年级</strong></th>
            <th class="text-center"><strong>填写人姓名</strong></th>
            <th class="text-center"><strong>与填写人关系</strong></th>
            <th class="text-center"><strong>连络电话</strong></th>
            <th class="text-center"><strong>连络信箱</strong></th>
        </tr>
        <tr>
            <td>
                <input type="text" name="school_name" class="form-control" id="school_name" value="{{ $cxt->school_name }}">
            </td>
            <td>
                <input type="number" name="grade_num" class="form-control" id="grade_num" value="{{ $cxt->grade_num }}">
            </td>
            <td>
                <input type="text" name="filler_name" class="form-control" id="filler_name" value="{{ $cxt->filler_name }}">
            </td>
            <td>
                <input type="text" name="relation" class="form-control" id="relation" value="{{ $cxt->relation }}">
            </td>
            <td>
                <input type="text" name="phone" class="form-control" id="phone" value="{{ $cxt->phone }}" readonly>
            </td>
            <td>
                <input type="email" name="email" class="form-control" id="email" value="{{ $cxt->email }}">
            </td>
        </tr>
    </tbody>
</table>
<!-- #######################################################################################-->
<h2><strong>填表说明</strong></h2>
<p class="lead">本量表一共60题，请根据孩子的日常表现客观勾选，并在「其他意见或观察」栏填写额外的补充、说明。</p>
                                        
<p class="lead">各反应频率界定如下：</p>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center"><strong>項目</strong></th>
            <th class="text-center"><strong>說明</strong></th>
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
<h4 class="text-right"><strong>评测专业人员：</strong></h4> 