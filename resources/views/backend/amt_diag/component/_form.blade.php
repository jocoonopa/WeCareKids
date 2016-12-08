{{ csrf_field() }}

<input type="hidden" name="group_id" value="{{$group->id}}">

<div class="form-group">
    {!! Form::label('description', '題目描述') !!}
    {!! Form::text('description', $diag->content, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('type', '類型') !!}

    <select name="type" id="type" class="form-control">
        @foreach ([
            \App\Model\AmtDiag::TYPE_SWITCH_ID => '是非', 
            \App\Model\AmtDiag::TYPE_SLIDER_ID => '範圍', 
            \App\Model\AmtDiag::TYPE_RADIO_ID => '單選'
        ] as $value => $name)
            <option value="{{$value}}" @if($value === $diag->type) selected @endif>{{$name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    {!! Form::label('available_value', '可用範圍值') !!}
    {!! Form::text(
        'available_value', 
        $diag->available_value, 
        [
            'class' => 'form-control', 
            'placeholder' => '是非題: 0, 範圍題: {"m": 0, "M": 99}, 單選: ["好吃","普通", "難吃"]']) 
    !!}
</div>

<div class="form-group">
    <button type="submit" class="btn btn-lg btn-primary">確認</button>
</div>