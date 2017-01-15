{{-- menu: ['name':, 'child':] --}}
@foreach ($menus as $content => $menu) 
    <?php $category = array_get($menu, \App\Model\AmtCategory::RECURSIVE_CURRENT_KEY); ?>
    <?php $child = array_get($menu, \App\Model\AmtCategory::RECURSIVE_CHILD_KEY); ?>
    <li>
        {{$category->id}}:{{$category->content}} 
    </li>
        
    @if(!is_null($child))
    <ul>
        @include('backend/amt/component/_menu', ['menus' => $child, 'amt' => $amt])
    </ul>
    @endif

    @if(true === $category->isFinal())
    <ul>
        @foreach ($category->groups()->where('amt_id', $amt->id)->get() as $group)
        <li>
            <span class="text-success">{{ $group->content }}</span>
            <a href="/backend/amt/{{ $amt->id }}/amt_diag_group/{{ $group->id }}/edit" class="btn btn-xs btn-primary">
                <i class="glyphicon glyphicon-edit"></i>
            </a>
        </li>
        
        <ul>                        
            <li>
                <a href="/backend/amt_diag_group/{{$group->id}}/amt_diag">
                    <i class="fa fa-file-text-o"></i> 
                    评测问题列表
                </a>
            </li>

            <li>
                <a href="/backend/amt_diag_group/{{$group->id}}/amt_diag_standard">
                    <i class="fa fa-file-text-o"></i>
                    评测标准列表
                </a>
            </li>

            <li>
                <a href="/backend/amt_diag_group/{{$group->id}}/amt_cell">
                    <i class="fa fa-file-text-o"></i>
                    网格列表
                </a>
            </li>                           
        </ul>
        @endforeach
    </ul>    
    @endif
@endforeach