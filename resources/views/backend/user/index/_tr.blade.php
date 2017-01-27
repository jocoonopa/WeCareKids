<tr>                        
    <td>
        <a href="/backend/user/{{$user->id}}">
            {{$user->name}}
        </a>

        <span class="badge">
            {{$user->getJobTitle()}}
        </span>
    </td>
    
    <td>
        @if (is_null($user->organization))
            <span class="label label-default">未指定所属组织</span>
        @else
            <span class="label label-success">
                {{$user->organization->name}}
            </span>
        @endif
    </td>

    <td>
        <a href="mailto:{{$user->email}}">{{$user->email}}</a>                            
    </td>

    <td>
        <a href="tel:{{$user->phone}}">
            {{$user->phone}}
        </a>
    </td>

    <td>
        {{ "{$user->cxts->count()}笔" }}
    </td>

    <td>
        {{ "{$user->replicas->count()}笔" }}
    </td>     

    <td>
        @if($user->trashed())
            <span class="label label-danger">
                停用
            </span>
        @else
            <span class="label label-success">
                启用
            </span>
        @endif
    </td>

    @can('update', $user)
        <td>
            <a href="/backend/user/{{$user->id}}/reset" class="btn btn-default btn-sm pull-left">
                <i class="fa fa-edit"></i>
                修改密码
            </a>

            <a href="/backend/user/{{$user->id}}/edit" class="btn btn-primary btn-sm pull-left">
                <i class="fa fa-edit"></i>
                编辑
            </a>       
            @if ($user->trashed())                                
                {!! Form::model($user, [
                    'url' => "/backend/user/{$user->id}/restore", 
                    'method' => 'put', 
                    'class' => 'pull-left',
                ]) !!}
                    <button type="submit" class="btn btn-default btn-sm pull-left" onclick="return confirm('确定启用{{$user->name}}吗?')">
                        <i class="fa fa-check-circle-o"></i>
                        启用
                    </button>
                {!! Form::close() !!}
            @else
                {!! Form::model($user, [
                    'url' => "/backend/user/{$user->id}",
                    'method' => 'delete',
                    'class' => 'pull-left',
                ]) !!}
                    <button type="submit" class="btn btn-danger btn-sm pull-left" onclick="return confirm('确定停用{{$user->name}}吗?')">
                        <i class="fa fa-remove"></i>
                        停用
                    </button>
                {!! Form::close() !!}
            @endif                                                 
        </td>
    @endcan
</tr>