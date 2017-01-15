@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>评测考卷编辑区</h3>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        @foreach ($amt->groups as $group)
        <div class="col-md-4 col-sm-12 col-xs-12"">
            <table>
                <thead></thead>
                <tbody>
                    <tr>
                        <td>
                            <a href="">
                                {{$group->id}}    
                            </a>
                        </td>
                        <td>
                            {{ $group->content }}
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>            
        </div>
        @endforeach
    </div>
</div>
@endsection