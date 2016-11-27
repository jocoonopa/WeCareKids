@extends('layouts.land')

@section('main_container')
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-sm-12">
        @include('backend/als_rpt_cxt/index/form')
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">

$(function () {
    $('#child_birthday').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        locale: 'zh-cn',
        viewMode: 'years'
    });

    $('#child_birthday').blur(function () {
        let birthday = new Date($(this).val());

        let age = calculateAge(birthday);

        $('#child_age').text(age);
    });
});

</script>
@endpush