<tr>
    <td>
        @if ($replica->isDone())
            <a href="/backend/amt_als_rpt/{{$replica->report->id}}">
                <i class="fa fa-external-link"></i>
                报告连结
            </a>
        @else
            <span class="label label-danger">尚未作答完毕</span>
        @endif
    </td>
    <td>
        {{ $replica->created_at }}
        <a href="/backend/amt_replica/{{$replica->id}}">
            <i class="fa fa-external-link"></i>
        </a>
    </td>
    <td>
        @if ($replica->report->cxt)
            {{ $replica->report->cxt->created_at }}
            <a href="/backend/analysis/r/i/cxt/{{$replica->report->cxt->id}}">
                <i class="fa fa-external-link"></i>
            </a>
        @else
            <span class="label label-danger">无对应问卷</span>
        @endif
    </td>
</tr>