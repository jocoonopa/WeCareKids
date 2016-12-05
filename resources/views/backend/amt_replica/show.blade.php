@extends('layouts.land')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
            <h1>
                {{$replica->id}}

                <small>
                    <a href="/backend/amt_replica/{{$replica->id}}/edit" class="btn btn-default btn-sm">
                        修改
                    </a>
                </small>
            </h1>
            <ul>
                @foreach ($replica->groups()->get() as $replicaGroup)
                    <li @if ($replicaGroup->isDone()) class="text-success" @endif>{{$replicaGroup->group->content}}</li>
                    <ul>
                        @foreach ($replicaGroup->diags()->get() as $replicaDiag)
                            <li @if (!is_null($replicaDiag->value)) class="text-success" @endif>
                                {{ "{$replicaDiag->id}:{$replicaDiag->diag->description}" }}
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </ul>
            
        </div>
    </div>
</div>
@endsection