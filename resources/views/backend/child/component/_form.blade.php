<div class="form-group">
    {!! Form::label('name', '姓名') !!}
    {!! Form::text('name', $child->name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('birthday', '生日') !!}
    {!! Form::date('birthday', $child->birthday, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('school_name', '学校名称') !!}
    {!! Form::text('school_name', $child->school_name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('grade', '学校年级') !!}
    {!! Form::text('grade', $child->grade, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('sex', '性别') !!}
    <div>
        {!! Form::label('sex', '女') !!}
        {!! Form::radio('sex', 0, (false === (bool) $child->sex)) !!}

        {!! Form::label('sex', '男') !!}
        {!! Form::radio('sex', 1, (true === (bool) $child->sex)) !!}
    </div>

</div>

<div class="form-group">
    <button type="submit" class="btn btn-success btn-sm">
        <i class="fa fa-check-circle-o"></i>
        确认
    </button>

    <a href="/backend/child" class="btn btn-default btn-sm">
        <i class="fa fa-circle-o"></i>
        取消
    </a>
</div>
