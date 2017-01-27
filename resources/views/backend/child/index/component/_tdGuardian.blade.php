<?php $gAttrs = ['name', 'mobile', 'email']; ?>
@if(0 === $child->guardians->count())
    @foreach ($gAttrs as $gAttr)
        <td>
            <span class="label label-default">尚无资讯</span>
        </td>
    @endforeach
@else
    @foreach ($gAttrs as $gAttr)
        <td>
            @foreach ($child->guardians as $guardian)
                @if ('email' == $gAttr)
                    <a href="mailto: {{ $guardian->$gAttr }}">
                        {{ $guardian->$gAttr }}
                    </a>
                @else
                    {{ $guardian->$gAttr }}
                @endif
            @endforeach
        </td>
    @endforeach
@endif
{{-- 家长资讯 //--}}

@if (is_null($lastReplica))
    {{-- 问卷 --}}
    <td>
        <span class="label label-default">尚无资讯</span>
    </td>
    {{-- 测评 --}}
    <td>
        <span class="label label-default">尚无资讯</span>
    </td>
@else
    {{-- 问卷 --}}
    <td>
        <p>{{ \App\Model\AmtReplica::fetchWithCxtFromCollection($notExpiredReplicas)->count() . "笔" }}</p>

        @if ($lastReplica->report->cxt)
            {{ $lastReplica->report->cxt->created_at }}
        @endif
    </td>
    {{-- 测评 --}}
    <td>
        <p>{{ "{$notExpiredReplicas->count()}笔" }}</p>
        {{ $lastReplica->created_at }}
        
        @if ($lastReplica->report->cxt)
            <span class="label label-warning">
                {{ "测评剩余{$lastReplica->getExpiredCountdown()}天过期" }}
            </span>
        @endif
    </td>                            
@endif   