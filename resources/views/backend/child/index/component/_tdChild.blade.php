<a href="{{"/backend/child/{$child->id}"}}" class="pull-left btn btn-info btn-xs" target="_blank">
    <i class="fa fa-eye"></i>
    檢視
</a> 

{{-- <a href="{{"/backend/child/{$child->id}/edit"}}" class="pull-left btn btn-primary btn-xs" target="_blank">
    <i class="fa fa-edit"></i>
    编辑
</a>   --}}

<form class="pull-left form-inline" action="/backend/child/{{$child->id}}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="delete" />
    <button type="submit" class="pull-left btn btn-danger btn-xs" onclick="return confirm('确定删除{{$child->name}}吗?');">
        <i class="fa fa-remove"></i>
        删除
    </button>
</form>