@extends('layout.modal')

@section('title', __('Form Import'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => $module . '.importuser', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group m-0 row p-0">
            <label for="name" class="col-sm-3 col-form-label">{{ __('File') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input class="form-control " name="files" type="file" accept=".xlsx">
                <div class="error_files"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Import') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

@push('plugin-scripts')
<script type="text/javascript">
  $(function(){
    initPage();

    $('form#my-form').submit(function(e){
      e.preventDefault();
      $(this).myAjax({
          waitMe: '.modal-content',
          success: function (data) {
              $('.modal').modal('hide');
              oTable.reload();
          },
          error: function(data){
            console.log(data);
            var response = data.responseJSON;
            Swal.fire('Error', response.message, 'error');
            // $('.error_files').html(data.data)
          }
      }).submit();
    });
  })
</script>
@endpush
