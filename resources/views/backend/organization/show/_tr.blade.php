<tr>
    <td>{{$usage->created_at->format('Y-m-d')}}</td>
    <td>{{$usage->created_at->format('H:i:s')}}</td>
    <td>
        @if(is_null($usage->usage))
            {{'已删除'}}
        @else
            {{$usage->usage->getUsageDesc()}}
        @endif
    </td>
    <td>{!! $usage->getVarietyDesc() !!}</td>
    <td>{{$usage->current_remain}}</td>
</tr>