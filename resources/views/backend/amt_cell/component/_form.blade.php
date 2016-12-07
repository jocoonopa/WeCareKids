{{ csrf_field() }}

<input type="hidden" name="group_id" value="{{$group->id}}">

<div class="form-group">
    {!! Form::label('statement', '條件語句') !!}
    {!! Form::text('statement', $cell->statement, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('league_id', 'League Id') !!}
    
    <select name="league_id" id="league_id" class="form-control">
        <option value="0" @if (is_null($cell->league_id)) selected @endif>NULL</option>

        @foreach ($cells as $_cell)
        <option value="{{$_cell->id}}" @if(!is_null($cell->league_id) && ($_cell->league_id === $cell->league_id)) selected @endif>{{$_cell->id}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    {!! Form::label('step', '閥值') !!}
    {!! Form::number('step', $cell->step, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <button type="submit" data-id="{{$cell->id}}" class="btn btn-lg btn-primary">確認</button>
</div>