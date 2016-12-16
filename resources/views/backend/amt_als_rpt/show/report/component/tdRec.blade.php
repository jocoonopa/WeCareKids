@foreach ($childCategory->childs as $childCategory)
    @if (true === $childCategory->isFinal())
    <?php $actualLevel = \AmtAlsRpt::getLevelByCategory($report, $childCategory); ?>
    <tr>
        <td class="lead" colspan="{{$colSpanCount}}">{{$childCategory->content}}</td>

        @if (empty($actualLevel))
            <td class="active" colspan="2">不施测</td>
        @else
            <td class="lead">{{ $actualLevel }}</td>
            <td class="lead">{{ $report->replica->getLevel() }}</td>
        @endif
    </tr>
    @else
        <?php $posteritys = []; $childCategory->findPosterity($posteritys);?>
        <tr>
            <td class="lead" rowspan="{{count($posteritys) + 1}}">{{$childCategory->content}}</td>
        </tr>

        @include('backend/amt_als_rpt/show/report/component/tdRec', [
            'childCategory' => $childCategory, 
            'colSpanCount' => ($colSpanCount - 1)
        ])
    @endif
@endforeach