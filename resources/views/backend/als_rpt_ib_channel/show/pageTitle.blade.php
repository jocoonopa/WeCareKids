<div class="title_left">
    <h3>
        {!! $channel->getStatusDesc(true) !!}
        評量頻道
        <small>
            {{ "{$channel->open_at->format('Y-m-d')} ~ {$channel->close_at->format('Y-m-d')}" }}
        </small>
    </h3>
</div>
<div class="title_right"> 
    <a href="/backend/analysis/r/i/channel" class="btn btn-sm btn-primary pull-right">
        <i class="fa fa-arrow-circle-o-left"></i>
        回到列表
    </a>
</div>
<div class="clearfix"></div>