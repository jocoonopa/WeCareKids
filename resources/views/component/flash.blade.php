@if (!is_null(Session::get('success')))
<div class="alert alert-success" role="alert">
    {{ Session::get('success') }}
</div>
@endif
@if (!is_null(Session::get('error')))
<div class="alert alert-danger" role="alert">
    {{ Session::get('error') }}
</div>
@endif