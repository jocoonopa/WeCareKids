@foreach ($childCategory->childs as $childCategory)
    @if (true === $childCategory->isFinal())
    <tr>
        <td colspan="{{$colSpanCount}}">{{$childCategory->content}}</td>
        <td>{{ \AmtAlsRpt::getLevelByCategory($report, $childCategory) }}</td>
        <td>{{ $report->replica->getLevel() }}</td>
    </tr>
    @else
        <?php $posteritys = []; $childCategory->findPosterity($posteritys);?>
        <tr>
            <td rowspan="{{count($posteritys) + 1}}">{{$childCategory->content}}</td>
        </tr>

        @include('backend/amt_als_rpt/show/report/component/tdRec', [
            'childCategory' => $childCategory, 
            'colSpanCount' => ($colSpanCount - 1)
        ])
    @endif
@endforeach