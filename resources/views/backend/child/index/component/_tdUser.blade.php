@if (0 < $child->users->count())
    <a href="/backend/user/{{ $child->users->first()->id }}">
        {{ $child->users->first()->name }}
    </a>
@else
    <span class="label label-warning">尚无对应</span>
@endif