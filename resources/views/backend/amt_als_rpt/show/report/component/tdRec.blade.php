@foreach ($childCategory->childs as $child)
    @if (true === $child->isFinal())
    <tr>
        <td colspan="{{$colSpanCount}}">{{$child->content}}</td>
        <td>{{ \AmtAlsRpt::getLevelByCategory($report, $child) }}</td>
        <td>{{ $report->replica->getLevel() }}</td>
    </tr>
    @else
    
    <?php $posteritys = []; $child->findPosterity($posteritys);?>

        @if ($child->isStat())
        <tr>
            <td rowspan={{count($posteritys) + 1}}>{{$child->content}}</td>
        </tr>

        @else
        <tr>
            <td colspan="1" rowspan="{{count($posteritys) + 1}}">{{$child->content}}</td>
        </tr>
        @endif

        @include('backend/amt_als_rpt/show/report/component/tdRec', [
            'childCategory' => $child, 
            'colSpanCount' => ($colSpanCount - 1)
        ])
    @endif
@endforeach