@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('component/flash')
        
        <h3>
            <small>
                <a href="/backend/amt/{{$group->amt->id}}" class="pull-left btn btn-default">大題列表</a>
            </small>

            {{$group->content}}
        </h3>

        <a class="pull-right btn btn-warning btn-sm" href="/backend/amt_diag_group/{{$group->id}}/amt_diag_standard" target="_blank">
            標準參考視窗
        </a>

        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id + 1) . "/amt_cell"}}">
            切換到下一個大題
        </a>
        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id - 1) . "/amt_cell"}}">
            切換到上一個大題
        </a>    
    </div>

    @foreach ($group->cells as $cell)
    <div id="__{{$cell->id}}__" class="col-md-offset-1 col-md-10 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ "Level:{$cell->level}" }} <small>{{$cell->id}}</small></h3>
            </div>
            <div class="panel-body">
                <ul>
                    @foreach($cell->standards as $standard)                            
                    <li>
                        <a href="{{"/backend/amt_diag_group/{$group->id}/amt_diag/{standard->id}/edit"}}">
                            {{ $standard->id }}:{{ "level{$standard->min_level} ~ level{$standard->max_level}{$standard->condition_value}" }}
                        </a> 
                    </li>                                      
                    @endforeach
                </ul>                                                   
            </div>
            <div class="panel-footer">
                {!! Form::model($cell, [
                    'url' => "/backend/amt_diag_group/{$group->id}/amt_cell/{$cell->id}", 
                    'method' => 'put'
                ]) !!}
                    @include('backend/amt_cell/component/_form', ['group' => $group, 'cell' => $cell, 'cells' => $group->cells])
                {!! Form::close() !!}
            </div>
        </div>
    </div>
     @endforeach
</div>
@endsection

@push('scripts')
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('button[type="submit"]').click(function () {
    var $this = $(this);
    var id = $this.data('id');
    var $form = $this.closest('form');
    var url = $form.attr('action');
    var data = {
        "_method": "put",
        "league_id": $form.find('[name="league_id"]').val(),
        "statement": $form.find('[name="statement"]').val(),
        "step": $form.find('[name="step"]').val()
    };

    HoldOn.open({
        message:"儲存中請稍候..."
    });

    $.ajax({
        type: 'post',
        url: url,
        data: data,
        success: function (res) {
            $('#__' + id + '__').find('.panel-body').html(res);

            HoldOn.close();
        },
        error: function (res) {
            alert('error occured! Please check the console.');
            console.log('Error:', res);

            HoldOn.close();
        }
    });

    return false;
});


</script>
@endpush