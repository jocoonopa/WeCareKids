@if (!is_null($lastReplica))
    <a href="/backend/amt_als_rpt/{{ $lastReplica->report->id }}" class="pull-right btn btn-info btn-xs" target="_blank">
        <i class="fa fa-eye"></i>
        报告
    </a>
    
    @if (!is_null($lastReplica->report->cxt))
    <a href="/backend/analysis/r/i/cxt/{{ $lastReplica->report->cxt->id }}" class="pull-right btn btn-info btn-xs" target="_blank">
        <i class="fa fa-edit"></i>
        问卷
    </a>
    @endif
@endif

@if (!is_null($lastReplica) && \App\Model\AmtReplica::STATUS_ORIGIN_ID === $lastReplica->status)
    <a href="/backend/amt_replica/{{ $lastReplica->id }}/edit" class="pull-right btn btn-primary btn-xs" target="_blank" target="_blank">
        <i class="fa fa-pencil"></i>
        评测
    </a>
@else
    <form class="pull-right form-inline" action="/backend/amt_replica" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="child_id" value="{{$child->id}}" />
        <input type="hidden" name="amt_id" id="amt_id" value="{{ \App\Model\Amt::DEFAULT_AMT_ID }}" />
        
        <button type="submit" class="pull-right btn btn-success btn-xs">
            <i class="fa fa-plus-circle"></i>
            评测
        </button>
    </form>                            
@endif