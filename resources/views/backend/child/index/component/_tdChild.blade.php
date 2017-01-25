<form class="pull-right form-inline" action="/backend/child/{{$child->id}}" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="delete" />
    <button type="submit" class="pull-right btn btn-danger btn-xs" onclick="return confirm('确定删除{{$child->name}}吗?');">
        <i class="fa fa-remove"></i>
        删除
    </button>
</form>

<a href="{{"/backend/child/{$child->id}/edit"}}" class="pull-right btn btn-default btn-xs" target="_blank">
    <i class="fa fa-edit"></i>
    编辑
</a>  