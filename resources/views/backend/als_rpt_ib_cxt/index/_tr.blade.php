<tr>
    <td>{{ $cxt->id }}</td>
    <td>{{ $cxt->child_name }}</td>
    <td>{{ $cxt->filler_name }}</td>
    <td>
        <a href="tel:{{ $cxt->phone }} " class="text-info">
            {{ $cxt->phone }}
        </a>
    </td>   
    <td>
        <a href="mailto: {{$cxt->email}}" class="text-info">
            {{$cxt->email}}
        </a>
    </td>
    <td>{{ $cxt->created_at }}</td>
    <td>{{ $cxt->updated_at }}</td>
    <td>
        <span class="label {{\Wck::getCxtStatusLabel($cxt)}}">
            {{ $cxt->getStatusDesc() }}
        </span>                            
    </td>
    <td>
        <a href="{{"/backend/analysis/r/i/cxt/{$cxt->id}"}}" class="btn btn-xs btn-primary pull-left" target="_blank">
            <i class="fa fa-edit"></i>
            编辑
        </a>
        
        @can('delete', $cxt)
            {!! Form::model($cxt, [
                'url' => "/backend/analysis/r/i/cxt/{$cxt->id}", 
                'method' => 'DELETE',
                'class' => 'pull-left',
            ]) !!}
                <button type="submit" class="btn btn-danger btn-xs pull-left" onclick="return confirm('確定刪除{{$cxt->id}}嗎?');">
                    <i class="fa fa-remove"></i>
                    删除
                </button>
                
            {!! Form::close() !!}
        @endcan
    </td>
</tr>