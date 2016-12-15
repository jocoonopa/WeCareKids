{{ csrf_field() }}

<input type="hidden" name="group_id" value="{{$group->id}}">

<div class="form-group">
    {!! Form::label('description', '题目描述') !!}
    {!! Form::text('description', $diag->content, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('type', '类型') !!}

    <select name="type" id="type" class="form-control">
        @foreach (array_except(\App\Model\AmtDiag::$types, [\App\Model\AmtDiag::TYPE_CHECKBOX_ID]) as $value => $name)
            <option value="{{$value}}" @if($value === $diag->type) selected @endif>{{$name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    {!! Form::label('available_value', '可用范围值') !!}
    {!! Form::text(
        'available_value', 
        $diag->available_value, 
        [
            'class' => 'form-control', 
            'placeholder' => '是非题: 0, 范围题: {"m": 0, "M": 99}, 单选: ["好吃","普通", "难吃"], 阀值: {"l": "低", "e": "正常", "h": "高"}']) 
    !!}
</div>

<div class="form-group">
    <button type="submit" class="btn btn-lg btn-primary">确认</button>
</div>