@extends('layouts.land')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ "{$replica->child->name}{$replica->child->getSex()}@{$replica->created_at->format('Y-m-d')}"}} 
                        @if (\App\Model\AmtReplica::STATUS_DONE_ID === $replica->status)
                            <span class="label label-success">完成</span>
                        @endif
                    </h3>
                </div>
                <div class="panel-body">
                    <h4 class="text-center">
                        <a href="/backend/amt_replica" class="pull-left btn btn-default btn-sm">
                            列表
                        </a>
                        受測者預設等級:{{ $replica->child->getLevel($replica->created_at) }}
                        @if (\App\Model\AmtReplica::STATUS_DONE_ID !== $replica->status)
                        <a href="/backend/amt_replica/{{$replica->id}}/edit" class="pull-right btn btn-default btn-sm">
                            作答
                        </a>
                        @else
                        <a href="/backend/amt_als_rpt/{{$replica->report->id}}" class="pull-right btn btn-primary btn-sm">
                            報表
                        </a>
                        @endif
                    </h4>
                    <hr>
                    <ul>
                        @foreach ($replica->groups as $replicaGroup)
                            @if ($replicaGroup->isDone())
                                <li class="text-success">
                            @elseif ($replicaGroup->isSkip())
                                <li class="text-warning">
                            @endif
                                {{$replicaGroup->id}}:{{$replicaGroup->group->content}}
                                
                                @if ($replicaGroup->isDone() or $replicaGroup->isSkip())                                
                                    <span class="badge">
                                        {{ $replicaGroup->getLevel() }}
                                    </span>
                                @endif

                                <br>
                                
                                @if (!is_null($replicaGroup->resultCell))
                                    <code>{{ "{$replicaGroup->resultCell->id}:{$replicaGroup->resultCell->getChief()->statement}" }}</code>
                                @endif
                            </li>
                            <ul>
                                @foreach ($replicaGroup->diags as $replicaDiag)
                                    <li @if (!is_null($replicaDiag->value)) class="text-success" @endif>
                                        {{ "{$replicaDiag->id}:{$replicaDiag->diag->description}" }}
                                        
                                        @if (!is_null($replicaDiag->value)) 
                                            <span class="label label-danger">
                                                {{$replicaDiag->getUTF8value()}}
                                            </span>            
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection