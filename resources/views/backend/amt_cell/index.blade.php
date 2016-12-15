@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('component/flash')
        
        <h3>
            <small>
                <a href="/backend/amt/{{$group->amt->id}}" class="pull-left btn btn-default">大题列表</a>
            </small>

            <small>
                <a href="/backend/amt/{{$group->amt->id}}/map" class="pull-left btn btn-default" target="_blank">全图</a>
            </small>
        </h3>

        <a class="pull-right btn btn-warning btn-sm" href="/backend/amt_diag_group/{{$group->id}}/amt_diag_standard" target="_blank">
            标准参考视窗
        </a>

        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id + 1) . "/amt_cell"}}">
            切换到下一个大题
        </a>
        <a class="pull-right btn btn-info btn-sm" href="{{"/backend/amt_diag_group/" . ($group->id - 1) . "/amt_cell"}}">
            切换到上一个大题
        </a>    
    </div>

    {{-- @foreach($group->amt->groups as $group) --}}
    
    <div class="col-md-offset-1 col-md-10 col-sm-6 col-xs-12">
        <h4>{{$group->content}}</h4>

        @foreach ($group->cells as $cell)
        <div id="__{{$cell->id}}__" class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ "Level:{$cell->level}" }}</h3>
            </div>
            <div class="panel-body">
                <ul>
                    @include('backend/amt_cell/component/_response', ['cell' => $cell])
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
        @endforeach
    </div>
    {{-- @endforeach --}}
</div>
@endsection

@push('scripts')
<script>
$(function () {
    var isEdited = false;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var uploadProcess = function ($e) {
        var $this = $e;
        var $form = $this.closest('form');
        var url = $form.attr('action');
        var id = $form.find('button[type="submit"]').data('id');
        var data = {
            "_method": "put",
            "league_id": $form.find('[name="league_id"]').val(),
            "statement": $form.find('[name="statement"]').val(),
            "step": $form.find('[name="step"]').val()
        };

        HoldOn.open({
            message:"储存中请稍候..."
        });

        $.ajax({
            type: 'post',
            url: url,
            data: data,
            success: function (res) {
                $('#__' + id + '__').find('.panel-body').html(res);

                isEdited = false;

                HoldOn.close();
            },
            error: function (res) {
                alert('error occured! Please check the console.');
                
                isEdited = false;

                console.log('Error:', res);

                HoldOn.close();
            }
        });

        return false;
    };

    $('button[type="submit"]').click(function () {
        return uploadProcess($(this));
    });
    
    $('input[name="statement"]').add('input[name="step"]').blur(function () {
        if (true === isEdited) {
            return uploadProcess($(this));
        }
    });
    
    $('select[name="league_id"]').change(function () {
        return uploadProcess($(this));
    });

    $('input[name="statement"]').add('input[name="step"]').keyup(function () {
        isEdited = true;
    });
});


</script>
@endpush