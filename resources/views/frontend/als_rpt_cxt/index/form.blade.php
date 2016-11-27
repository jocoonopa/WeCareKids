<div class="x_panel" style="color: #424242;">                        
    <div class="text-center">
        <form action="{{ "/analysis/r/i/cxt/{$cxt->id}" }}" method="post">
            <input type="hidden" name="cxt_id" value="{{ $cxt->id }}" />
            <input type="hidden" name="private_key" value="{{ $privateKey}}" />

            @include('frontend/als_rpt_cxt/index/form/_button')
            @include('frontend/als_rpt_cxt/index/form/_head') 
            @include('frontend/als_rpt_cxt/index/form/_group01')                                    
            @include('frontend/als_rpt_cxt/index/form/_group02')
            @include('frontend/als_rpt_cxt/index/form/_group03')
            @include('frontend/als_rpt_cxt/index/form/_group04')
            @include('frontend/als_rpt_cxt/index/form/_group05')
            @include('frontend/als_rpt_cxt/index/form/_group06')
        </form>
        {{-- @include('frontend/als_rpt_cxt/index/form/_result') --}}
    </div>
</div>