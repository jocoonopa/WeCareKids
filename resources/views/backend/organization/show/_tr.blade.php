<tr>
    <td>{{$usage->created_at->format('Y-m-d')}}</td>
    <td>{{$usage->created_at->format('H:i:s')}}</td>
    <td>
        {!! $usage->brief !!}
    </td>
    <td>{!! $usage->getVarietyDesc() !!}</td>
    <td>{{$usage->current_remain}}</td>
</tr>