@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            @include('component/flash')

            <table class="table table-striper">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>受測人員</th>
                        <th>家長手機</th>
                        <th>時間</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>
                            <a href="{{"/backend/amt_als_rpt/{$report->id}"}}">
                                {{ $report->id }}
                            </a>                            
                        </td>
                        <td>
                            @if ($report->replica)
                                {{"{$report->replica->child->name}{$report->replica->child->getSex()}"}}
                                <a href="{{"/backend/amt_replica/{$report->replica}"}}" class="btn btn-xs btn-info">
                                    評測結果
                                </a>
                            @endif
                        </td>
                        <td>
                            @if ($report->cxt)
                                <span class="label label-danger">{{$report->cxt->phone}}</span>
                            @endif
                        </td>
                        <td>
                            {{$report->updated_at->format('Y-m-d')}}
                        </td>
                    </tr>
                @endforeach                    
                </tbody>
            </table>            
        </div>
    </div>
</div>
@endsection
