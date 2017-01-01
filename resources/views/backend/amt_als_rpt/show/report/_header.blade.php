<div class="text-center">
    <h3>{{ "{$child->name}" }}</h3>
    <p class="lead">的天赋能力测评结果</p>
    <ul class="list-inline">
        <li class="lead">施测日期：{{ $report->replica->created_at->format('Y-m-d') }}</li>
        <li class="lead">出生日期：{{ $child->birthday->format('Y-m-d') }}</li>
        <li class="lead">实足年龄：{{ \App\Model\Child::getYMAge($child->birthday) }}</li>
    </ul>
    <br/>
    
    <h1>
        <span class="label label-primary">一、个体天赋能力分布</span>
    </h1>
    <br/>
</div>