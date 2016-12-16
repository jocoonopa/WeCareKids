<div class="text-center">
    <h1>{{ "{$child->name}{$child->getSex()}" }}</h1>
    <p>的天赋能力测评结果</p>
    <ul class="list-inline">
        <li>施测日期：{{ $report->replica->created_at->format('Y-m-d') }}</li>
        <li>出生日期：{{ $child->birthday->format('Y-m-d') }}</li>
        <li>实足年龄：{{ \App\Model\Child::getYMAge($child->birthday) }}</li>
    </ul>
    <br/>
    
    <h1>
        <span class="label label-primary">一、个体天赋能力分布</span>
    </h1>
    <br/>
</div>