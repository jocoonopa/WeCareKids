<form class="form-horizontal" action="{{"/backend/analysis/r/i/channel/{$channel->id}"}}" method="post">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="put" />
    <input type="hidden" name="open_at" value="{{$channel->open_at}}" />
    <input type="hidden" name="close_at" value="{{$channel->close_at}}" />

    <div class="form-group">
        <label class="col-md-2 control-label" for="is_open">是否開放</label>
        <input id="is_open" name="is_open" class="form-control" type="checkbox" value="1" @if ($channel->is_open) checked @endif/>
    </div>
    
    <div class="form-group">
        <label class="col-md-2 control-label" for="open_at">開始時間-截止時間</label>
        
        <div class="col-md-10">
            <input type="text" data-open="{{$channel->open_at}}" data-close="{{$channel->close_at}}" class="daterange form-control"/>          
        </div>
    </div>

    <div class="col-md-12 form-group">
        <button type="submit" class="btn btn-primary pull-right">
            <i class="fa fa-check-circle"></i>
            確認
        </button>
    </div>
</form>