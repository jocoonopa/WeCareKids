@extends('layouts.land')

@section('main_container')
<div class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
<div class="modal-content">
  
  <div class="modal-body">
    <div class="alert alert-success">填写完毕!</div>
  </div>
  
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@push('scripts')
<script>$('.modal').modal('show');</script>
@endpush