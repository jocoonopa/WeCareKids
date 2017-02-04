<div class="x_panel" style="color: #424242;">                        
    <div>
        {!! Form::model($cxt, ['url' => "/analysis/r/i/cxt/{$cxt->id}/submit", 'method' => 'put']) !!}
            <input type="hidden" name="cxt_id" value="{{ $cxt->id }}" />
            <input type="hidden" name="private_key" value="{{ $privateKey}}" />
            <input type="hidden" name="content" value="" />  

            @include('frontend/als_rpt_ib_cxt/index/form/_button')
            @include('frontend/als_rpt_ib_cxt/index/form/_head') 
            @include('frontend/als_rpt_ib_cxt/index/form/_group01')                                    
            @include('frontend/als_rpt_ib_cxt/index/form/_group02')
            @include('frontend/als_rpt_ib_cxt/index/form/_group03')
            @include('frontend/als_rpt_ib_cxt/index/form/_group04')
            @include('frontend/als_rpt_ib_cxt/index/form/_group05')
            @include('frontend/als_rpt_ib_cxt/index/form/_group06')

            <div class="form-group">
                @if ($cxt->isNotSubmit())
                    <button type="submit" class="btn btn-primary">提交</button>
                @endif
            </div>
        {!! Form::close() !!}
    </div>
</div>