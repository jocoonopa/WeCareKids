<div class="x_content">
    <span class="text-muted font-13 m-b-30">
        SomeDescription
    </span>
    <div class="pull-right">
        <form action="/backend/analysis/r/i/channel" method="post">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-default btn-sm">
                <i class="fa fa-plus-circle"></i>
            </button>
        </form> 
    </div>
</div> 

<table id="datatable" class="table table-striped">
    <thead>
        <tr>
            <th>id</th>
            <th>公鑰</th>
            <th>狀態</th>
            <th>開始時間</th>
            <th>截止時間</th>
            <th>建立時間</th>
            <th>動作</th>
        </tr>
    </thead> 
    <tbody>
        @foreach ($channels as $channel)
        <tr>
            <td>
                <a href="{{ "/backend/analysis/r/i/channel/{$channel->id}" }}">{{ $channel->id }}</a>
            </td>
            <td>{{ $channel->public_key }}</td>
            <td>{!! $channel->getStatusDesc(true) !!}</td>
            <td>{{ $channel->open_at->format('Y-m-d') }}</td>
            <td>{{ $channel->close_at->format('Y-m-d') }}</td>
            <td>{{ $channel->created_at->format('Y-m-d') }}</td>
            <td>
                <a href="{{"/analysis/r/i/channel/{$channel->id}/cxt?public_key={$channel->public_key}"}}" target="_blank">
                    GO
                </a>
                <a href="{{"/backend/analysis/r/i/channel/{$channel->id}/qrcode"}}" target="_blank">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="{{"/backend/analysis/r/i/channel/{$channel->id}/edit"}}">
                    <i class="fa fa-edit"></i>
                </a>  
            </td>
        </tr>
        @endforeach
    </tbody>
</table>