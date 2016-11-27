<div class="x_panel" style="color: #424242;">                        
    <div class="text-center">
        <form action="{{ "/analysis/r/i/cxt/{$cxt->id}" }}" method="post">
            <input type="hidden" name="cxt_id" value="{{ $cxt->id }}" />
            <input type="hidden" name="private_key" value="{{ $privateKey}}" />

            @include('backend/als_rpt_cxt/index/form/_button')
            @include('backend/als_rpt_cxt/index/form/_head') 
            @include('backend/als_rpt_cxt/index/form/_group01')                                    
            @include('backend/als_rpt_cxt/index/form/_group02')
            @include('backend/als_rpt_cxt/index/form/_group03')
            @include('backend/als_rpt_cxt/index/form/_group04')
            @include('backend/als_rpt_cxt/index/form/_group05')
            @include('backend/als_rpt_cxt/index/form/_group06')
        </form>
        {{-- @include('backend/als_rpt_cxt/index/form/_result') --}}
    </div>
</div>