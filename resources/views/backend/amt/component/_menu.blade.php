{{-- menu: ['name':, 'child':] --}}
@foreach ($menus as $content => $menu) 
    <?php $category = array_get($menu, \App\Model\AmtCategory::RECURSIVE_CURRENT_KEY); ?>
    <?php $child = array_get($menu, \App\Model\AmtCategory::RECURSIVE_CHILD_KEY); ?>
    <li>
        {{$category->content}} 
    </li>
        
    @if(!is_null($child))
    <ul>
        @include('backend/amt/component/_menu', ['menus' => $child, 'amt' => $amt])
    </ul>
    @endif

    @if(true === (bool) $category->is_final)
    <ul>
        @foreach ($category->groups()->where('amt_id', $amt->id)->get() as $group)
        <li>
            <p class="text-success">{{ $group->content }}</p>
        </li>
        
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
        @endforeach
    </ul>    
    @endif
@endforeach