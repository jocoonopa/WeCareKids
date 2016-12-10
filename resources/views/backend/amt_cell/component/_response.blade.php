@foreach($cell->standards as $standard)                            
<li>
    <a href="{{"/backend/amt_diag_group/{$group->id}/amt_diag/{standard->id}/edit"}}">
        <span class="badge">{{$standard->id}}</span>
        {{ $standard->diag->description }}
        <span class="text-danger">{{ "{$standard->getCondDesc()}" }}</span>
    </a> 
</li>                                      
@endforeach