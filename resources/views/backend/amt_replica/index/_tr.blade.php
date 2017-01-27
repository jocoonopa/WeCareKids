<tr>
    <td>
        <a href="/backend/amt_replica/{{$replica->id}}" target="_blank">{{$replica->id}}</a>

        @if ($replica->isDone())
            <span class="label label-success">完成</span>
        @else
            <span class="label label-default">未开始</span>
        @endif
    </td>

    <td>
        <a href="/backend/child/{{$replica->child->id}}">
            {{ $replica->child->name }}
        </a>                            
    </td>

    <td>
        {{ $replica->created_at->format('Y-m-d H:i:s') }}
    </td>

    <td>     
        <a href="/backend/amt_replica/{{$replica->id}}" class="btn btn-info btn-xs pull-left" target="_blank">
            <i class="fa fa-eye"></i>
            檢視
        </a>                

        @if ($replica->isOrigin())
            <a href="/backend/amt_replica/{{$replica->id}}/edit" class="btn btn-xs btn-primary pull-left">
                <i class="fa fa-edit"></i>
                繼續                                    
            </a>
        @endif
        
        @if (!$replica->isDone())
            <form action="/backend/amt_replica/{{$replica->id}}" class="pull-left" method="post" onsubmit="return confirm('确定删除吗?');">
                {{csrf_field()}}
                
                <input type="hidden" name="_method" value="delete">
                
                <button class="btn btn-danger btn-xs pull-left" >
                    <i class="fa fa-remove"></i>
                    删除
                </button>
            </form>               
        @endif             
    </td>
</tr>