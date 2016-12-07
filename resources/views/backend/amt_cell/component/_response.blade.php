@foreach($cell->standards as $standard)                            
<li>
    <a href="{{"/backend/amt_diag_group/{$group->id}/amt_diag/{standard->id}/edit"}}">
        {{ $standard->id }}:{{ "level{$standard->min_level} ~ level{$standard->max_level}{$standard->condition_value}" }}
    </a> 
</li>                                      
@endforeach