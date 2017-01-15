<div class="form-group">
    {!! Form::label('content', '標題描述') !!}
    {!! Form::text('content', $group->content, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <button type="submit" class="btn btn-primary">确认</button>
</div>
