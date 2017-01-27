<tr>
    <td>
        <a href="/backend/organization/{{$organization->id}}">
            {{ $organization->name }}
        </a>
    </td>
    <td>{{ $organization->region }}</td>
    <td>{{ $organization->account }}</td>
    <td>
        @if (0 < $organization->points)
        <span class="label label-success">
            {{ $organization->points }}元    
        </span>
        @else
        <span class="label label-danger">
            {{ $organization->points }}元    
        </span>
        @endif                            
    </td>
    <td>
        @if ($organization->contacter)
            <a class="text-primary" href="/backend/user/{{ $organization->contacter->id }}" target="_blank">
                {{ $organization->contacter->name }}
            </a>
        @else
            <span class="label label-warning">尚未指定联络人</span>
        @endif
    </td>
    <td>
        @if ($organization->owner)
            <a class="text-primary" href="/backend/user/{{ $organization->owner->id }}">
                {{ $organization->owner->name }}
            </a>
        @else
            <span class="label label-warning">尚未指定拥有人</span>
        @endif
    </td>
    <td>
        <a href="/backend/user?organization_id={{$organization->id}}">
            {{ $organization->users()->count() }}
        </a>
    </td>
    <td>
        <a href="/backend/organization/{{$organization->id}}" class="btn btn-info btn-xs pull-left">
            <i class="fa fa-eye"></i>
            检视
        </a>
        <a href="/backend/organization/{{$organization->id}}/edit" class="btn btn-primary btn-xs pull-left">
            <i class="fa fa-edit"></i>
            编辑
        </a>
        
        <form action="/backend/organization/{{$organization->id}}" class="pull-left" method="post" onsubmit="return confirm('確定要刪除嗎?');">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="delete">
            
            <button type="submit" class="btn btn-danger btn-xs pull-left">
                <i class="fa fa-remove"></i>
                刪除
            </button>
        </form>
        
    </td>
</tr>  