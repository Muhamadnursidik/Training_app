@extends('layout.modal')

@section('title', __('Form Edit Data Libur Nasional'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        
        {{-- Tanggal Libur --}}
        <div class="form-group row p-0 mb-1">
            <label for="tanggal" class="col-sm-3 col-form-label">{{ __('Tanggal') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="date" class="form-control" name="tanggal" id="tanggal" value="{{ $data->tanggal }}" required>
            </div>
        </div>

        {{-- Nama/Keterangan --}}
        <div class="form-group row p-0 mb-1">
            <label for="keterangan" class="col-sm-3 col-form-label">{{ __('Keterangan') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="keterangan" id="keterangan" value="{{ $data->keterangan }}" required>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

@push('plugin-scripts')
<script type="text/javascript">
  $(function(){
    initPage();

    // Ajax submit
    $('form#my-form').submit(function(e){
      e.preventDefault();
      $(this).myAjax({
          waitMe: '.modal-content',
          success: function (data) {
              $('.modal').modal('hide');
              oTable.reload();
          }
      }).submit();
    });
  })
</script>
@endpush
