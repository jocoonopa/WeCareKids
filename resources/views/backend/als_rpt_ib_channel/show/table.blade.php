<table class="table table-striped">
    <thead>
        <tr>
            <th>id</th>
            <th>家长姓名</th>
            <th>家长电话</th>
            <th>信箱</th>
            <th>小朋友姓名</th>
            <th>私钥</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($channel->cxts()->get() as $cxt) 
        <tr>
            <td>
                <a href="{{"/backend/analysis/r/i/cxt/{$cxt->id}"}}" target="_blank">
                    {{ $cxt->id }}
                </a>
            </td>
            <td>{{ $cxt->filler_name}}</td>
            <td>{{ $cxt->phone }}</td>
            <td>{{ $cxt->email}}</td>
            <td>{{ $cxt->child_name }}</td>
            <td>{{ $cxt->private_key }}</td>
        </tr>
        @endforeach
    </tbody>
</table>