@extends('layouts.blank')

@section('main_container')
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12">
            @include('component/flash')

            <h3>新增交易紀錄
                <small>
                    <a href="/backend/organization/{{$organization->id}}" class="btn btn-info btn-sm">回到組織詳細資料頁</a>
                </small>
            </h3>

            <form action="/backend/organization/{{$organization->id}}/wck_usage_record" method="post">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="variety">金额变量</label>
                    <input id="variety" name="variety" type="number" class="form-control" placeholder="+ 增加, - 減少"/>
                </div>
                
                <div class="form-group">
                    <label for="brief">交易描述</label>
                    <input id="brief" name="brief" type="text" class="form-control" placeholder="ex:設備費用" />
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check-circle"></i>
                        确认
                    </button>

                    <a href="/backend/organization/{{ $organization->id }}" class="btn btn-default">
                        <i class="fa fa-arrow-circle-left"></i>
                        取消
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#variety').focus();
</script>
@endpush
