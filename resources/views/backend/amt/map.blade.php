@extends('layouts.bootstrap')

@push('stylesheets')
<style>
.my-background {
    background-color: rgba(0, 0, 0, 0.36);   
}

.my-group {
    margin-left: 20px;
}
</style>

@endpush

@section('main_container')


<div id="my-container">
    @foreach ($amt->groups as $group)
    <div class="my-group" style="width: 400px; float: left;">
        <h4>
            <small>{{$group->id}}:</small>
            <small>
            {{ 26 > mb_strlen($group->content) ? $group->content : (mb_substr($group->content, 0, 26) . '...') }}</small>
        </h4>

        <table class="table table-bordered">
            <tbody>
                @foreach ($group->cells as $cell)
                    @if(0 === $cell->getChief()->standards->count())  
                    <tr>
                        <td>
                            {{$cell->level}}
                            <span class="label label-warning">{{$cell->id}}</span>
                            <span class="badge">{{$cell->league_id}}</span>
                        </td>
                        <td class="my-background"><div style="width:130px;height: 50px;"></div></td>
                        <td class="my-background"></td>
                    </tr>
                    @else
                    <tr>
                        <td>{{$cell->level}}
                            <span class="label label-warning">{{$cell->id}}</span>
                            <span class="badge">{{$cell->league_id}}</span>
                        </td>

                        @if (!is_null($cell->league_id) and $cell->league_id !== $cell->id)  
                            <td style="border-top: none;">
                                <span class="label label-info">
                                    {{ "同{$cell->league->level}"}}
                                </span>
                            </td>
                            <td></td>
                        @else
                            <td >
                                <ul>
                                    @foreach ($cell->standards as $standard)
                                        <li>
                                            <span class="badge">{{$standard->id}}</span>
                                            {{$standard->diag->description}}
                                            <span class="text-danger">
                                                {{ $standard->getCondDesc() }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                                <p>{{$cell->statement}}</p>
                            </td>
                            <td>{{$cell->step}}</td>
                        @endif                                                
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>            
    </div>
    @endforeach
</div>

@endsection

@push('scripts')
<script>
$(function () {
    var count = $('.my-group').length;

    $('#my-container').css('width', (count + 1) * (400 + 40));
})
</script>
@endpush
