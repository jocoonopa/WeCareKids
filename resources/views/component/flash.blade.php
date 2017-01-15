@if (!is_null(Session::get('success')))
<div class="alert alert-success" role="alert">
    {!! Session::get('success') !!}
</div>
@endif

@if (!is_null(Session::get('error')))
<div class="alert alert-danger" role="alert">
    {!! Session::get('error') !!}
</div>
@endif

@if (!is_null(Session::get('warning')))
<div class="alert alert-warning" role="alert">
    {!! Session::get('warning') !!}
</div>
@endif

@if (isset($errors) && count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif