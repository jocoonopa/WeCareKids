<?php $notExpiredReplicas = \App\Model\AmtReplica::fetchWithoutExpiredFromCollection($child->replicas); ?>
<?php $lastReplica = $notExpiredReplicas->last(); ?>

<tr>
    {{-- id --}}
    <td>
        <a href="/backend/child/{{$child->id}}" target="_blank">
            {{$child->id}}
        </a>                            
    </td>
    {{-- name --}}
    <td>{{ $child->name }}</td>
    {{-- birthday --}}
    <td>{{ $child->birthday->format('Y-m-d') }}</td>
    {{-- age --}}
    <td>{{ \App\Model\Child::getYMAge($child->birthday) }}</td>
    
    {{--// 家长资讯 --}}
    @include('backend/child/index/component/_tdGuardian', compact('lastReplica', 'child'))                         
    {{-- 家长资讯 //--}}

    {{-- 教师 --}}
    <td>
        @include('backend/child/index/component/_tdUser', compact('child'))
    </td>
    
    {{-- 問卷&評測 --}}
    <td>
        @include('backend/child/index/component/_tdAmt', compact('lastReplica', 'child'))
    </td>

    {{-- 操作 --}}
    <td>
        @include('backend/child/index/component/_tdChild', compact('child'))                         
    </td>
</tr>