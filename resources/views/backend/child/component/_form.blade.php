<div class="form-group">
    {!! Form::label('name', '姓名') !!}
    {!! Form::text('name', $child->name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('birthday', '生日') !!}
    {!! Form::date('birthday', $child->birthday, ['class' => 'form-control']) !!}
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
    <button type="submit" class="btn btn-default">确认</button>
</div>
