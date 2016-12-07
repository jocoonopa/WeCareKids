{{ csrf_field() }}

<input type="hidden" name="group_id" value="{{$group->id}}">

<div class="form-group">
    {!! Form::label('diag_id', '所屬問題') !!}

    <select name="diag_id" id="diag_id" class="form-control">
        @foreach ($group->diags as $diag)
            <option value="{{$diag->id}}" @if(!is_null($standard->diag) && $diag->id === $standard->diag->id) selected @endif>
                {{$diag->description}}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    {!! Form::label('min_level', 'level') !!}
    {!! Form::selectRange('min_level', 1, 20, ['class' => 'form-control']) !!}
    {!! Form::selectRange('max_level', 1, 20, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('condition_value', '條件值') !!}
    {!! Form::text(
        'condition_value', 
        $diag->condition_value, 
        [
            'class' => 'form-control', 
            'placeholder' => '是非題: 0, 範圍題: {"m": 0, "M": 99}, 單選: ["好吃","普通", "難吃"]']) 
    !!}
</div>

<div class="form-group">
    <button type="submit" class="btn btn-lg btn-primary">確認</button>
</div>