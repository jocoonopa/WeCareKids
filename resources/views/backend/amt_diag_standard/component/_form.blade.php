{{ csrf_field() }}

<input type="hidden" name="group_id" value="{{$group->id}}">

<div class="form-group">
    {!! Form::label('diag_id', '所属问题') !!}

    <select name="diag_id" id="diag_id" class="form-control">
        @foreach ($group->diags as $diag)
            <option value="{{$diag->id}}" @if(!is_null($standard->diag) && $diag->id === $standard->diag->id) selected @endif>
                {{$diag->description}}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    {!! Form::label('min_level', 'Min Level') !!}

    <select name="min_level" id="min_level" class="form-control">
        @for ($i = 1; $i <= 20; $i ++)
            <option value="{{$i}}" @if($i === $standard->min_level) selected @endif>{{$i}}</option>
        @endfor
    </select>
</div>

<div class="form-group">
    {!! Form::label('max_level', 'Max Level') !!}

    <select name="max_level" id="max_level" class="form-control">
        @for ($i = 1; $i <= 20; $i ++)
            <option value="{{$i}}" @if($i === $standard->max_level) selected @endif>{{$i}}</option>
        @endfor
    </select>
</div>

<div class="form-group">
    {!! Form::label('condition_value', '条件值') !!}
    {!! Form::text(
        'condition_value', 
        $standard->condition_value, 
        [
            'class' => 'form-control', 
            'placeholder' => '是非题: 0, 范围题: {"m": 0, "M": 99}, 单选: ["好吃"], 阈值: NULL']) 
    !!}
</div>

<div class="form-group">
    <button type="submit" class="btn btn-lg btn-primary">确认</button>
</div>