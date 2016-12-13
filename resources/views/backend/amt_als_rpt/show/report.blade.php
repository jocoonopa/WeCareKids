<div class="x_panel" style="Color:#424242;">
    {{-- ok --}}
    @include('backend/amt_als_rpt/show/report/_header') 
    
    @include('backend/amt_als_rpt/show/report/_chart')
    
    @include('backend/amt_als_rpt/show/report/_dimTable')
    
    @include('backend/amt_als_rpt/show/report/_levelDesc')
    
    @include('backend/amt_als_rpt/show/report/_summary')
    
    @if (!is_null($report->cxtBelongs))
        @include('backend/amt_als_rpt/show/report/_quarDesc')
        @include('backend/amt_als_rpt/show/report/_quarTable')
    @endif
    
    @include('backend/amt_als_rpt/show/report/_amtTable')
    
    @include('backend/amt_als_rpt/show/report/_complexAmtTable')
    
    @include('backend/amt_als_rpt/show/report/_footer')
</div>