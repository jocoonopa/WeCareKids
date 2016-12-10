@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12"">
        <h1>v{{$amt->id}}
            <small>
                <a href="/backend/amt/{{$amt->id}}/map" class="pull-right btn btn-default" target="_blank">全圖</a>
            </small>
        </h1>
        <ul>
            @foreach ($amt->groups()->get() as $group)
            <li>
                <p class="text-success">{{ $group->content }}</p>
            </li>
            <li>
                <ul>                        
                    <li>
                        <a href="/backend/amt_diag_group/{{$group->id}}/amt_diag">
                            <i class="fa fa-file-text-o"></i> 
                            評測問題列表
                        </a>
                    </li>

                    <li>
                        <a href="/backend/amt_diag_group/{{$group->id}}/amt_diag_standard">
                            <i class="fa fa-file-text-o"></i>
                            評測標準列表
                        </a>
                    </li>

                    <li>
                        <a href="/backend/amt_diag_group/{{$group->id}}/amt_cell">
                            <i class="fa fa-file-text-o"></i>
                            網格列表
                        </a>
                    </li>                           
                </ul>
            </li>
            @endforeach
        </ul>        
        </div>
    </div>
</div>     
@endsection