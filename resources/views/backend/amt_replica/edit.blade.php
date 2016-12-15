@extends('layouts.land')

@push('stylesheets')
<link rel="stylesheet" href="/bower_components/ion_rangeSlider/css/ion.rangeSlider.css">
<link rel="stylesheet" href="/bower_components/ion_rangeSlider/css/ion.rangeSlider.skinFlat.css">
@endpush

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-offset-2 col-md-8 col-sm-12 col-xs-12"">
            @include('component/flash')
            
            <h2>{{ "Q:{$replica->currentGroup->group->content}" }} <small>level: {{$level}}</small></h2>
            
            <form action="/backend/amt_replica/{{ $replica->id }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put" />
                <input type="hidden" name="level" value="{{$level}}" />

                @foreach ($replicaDiags as $key => $replicaDiag)
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            {{ $replicaDiag->diag->description }}
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label>选项:</label>
                        </div>
                        @if (\App\Model\AmtDiag::TYPE_RADIO_ID === $replicaDiag->diag->type)
                            @foreach (json_decode($replicaDiag->diag->available_value, true) as $value)
                                <?php $arr = is_array(array_get($answer, $replicaDiag->id)) 
                                    ? array_get($answer, $replicaDiag->id) : []
                                ?>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="{{$replicaDiag->id}}[]" value="{{$value}}" @if(in_array($value, $arr)) checked @endif/>
                                        {{$value}}                                           
                                    </label>                                
                                </div>
                            @endforeach
                        @endif

                        @if (\App\Model\AmtDiag::TYPE_SWITCH_ID === $replicaDiag->diag->type)
                            <div class="radio">
                                <label>
                                    <input type="radio" name="{{$replicaDiag->id}}" value="1" @if(array_get($answer, $replicaDiag->id) == '1') checked @endif/>
                                    是                                           
                                </label>                                
                            </div>

                            <div class="radio">
                                <label>
                                    <input type="radio" name="{{$replicaDiag->id}}" value="0" @if(array_get($answer, $replicaDiag->id) == '0') checked @endif/>否                                           
                                </label>                                
                            </div>
                        @endif

                        @if (\App\Model\AmtDiag::TYPE_THREAD_ID === $replicaDiag->diag->type)
                            @foreach (json_decode($replicaDiag->diag->available_value, true) as $key => $value)
                                <?php $arr = is_array(array_get($answer, $replicaDiag->id)) 
                                    ? array_get($answer, $replicaDiag->id) : []
                                ?>
                                <div class="radio">
                                    <label>                                                                        
                                        <input type="radio" name="{{$replicaDiag->id}}[]" value="{{$key}}" @if(in_array($key, $arr)) checked @endif>
                                        {{$value}} 
                                    </label>
                                </div>
                            @endforeach
                        @endif

                        @if (\App\Model\AmtDiag::TYPE_SLIDER_ID === $replicaDiag->diag->type)
                            <input type="text" class="slider" data-min="{{json_decode($replicaDiag->diag->available_value, true)['m']}}" data-max="{{json_decode($replicaDiag->diag->available_value, true)['M']}}" data-step="{{array_get(json_decode($replicaDiag->diag->available_value, true), 1)}}" name="{{$replicaDiag->id}}" value="{{ array_get($answer, $replicaDiag->id, '') }}" />
                        @endif
                    </div>
                </div>
                @endforeach

                <a href="/backend/amt_replica/{{$replica->id}}/prev" class="btn btn-default pull-left">上一题</a>
                <button type="submit" class="btn btn-default pull-right">下一题</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/bower_components/ion_rangeSlider/js/ion.rangeSlider.min.js"></script>
<script>
$('.slider').each(function () {
    var $this = $(this);

    $(this).ionRangeSlider({
        min: $this.data('min'),
        max: $this.data('max'),
        step: $this.data('step')
    }); 
});

</script>

@endpush
