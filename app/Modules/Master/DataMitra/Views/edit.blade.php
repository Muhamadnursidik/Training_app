@extends('layout.modal')

@section('title', __('Form Edit Data Mitra'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', 'datamitra' => encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group row p-0 mb-1">
            <label for="kode_mitra" class="col-sm-3 col-form-label">{{ __('Kode Mitra') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="kode_mitra" id="kode_mitra" value="{{ old('kode_mitra', $data->kode_mitra) }}">
            </div>
        </div>
        <div class="form-group row p-0 mb-1">
            <label for="nama_mitra" class="col-sm-3 col-form-label">{{ __('Nama Mitra') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="nama_mitra" id="nama_mitra" value="{{ old('nama_mitra', $data->nama_mitra) }}">
            </div>
        </div>
        <div class="form-group row p-0 mb-1">
            <label for="alamat" class="col-sm-3 col-form-label">{{ __('Alamat') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <textarea class="form-control" name="alamat" id="alamat" rows="3">{{ old('alamat', $data->alamat) }}</textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
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
          }
      }).submit();
    });

    // Validation rules (optional)
    $('form#my-form').validate({
        rules: {
            kode_mitra: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            nama_mitra: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            alamat: {
                required: true,
                minlength: 5,
                maxlength: 255
            }
        },
        messages: {
            kode_mitra: {
                required: "Kode Mitra wajib diisi",
                minlength: "Kode Mitra minimal 2 karakter",
                maxlength: "Kode Mitra maksimal 50 karakter"
            },
            nama_mitra: {
                required: "Nama Mitra wajib diisi",
                minlength: "Nama Mitra minimal 3 karakter",
                maxlength: "Nama Mitra maksimal 100 karakter"
            },
            alamat: {
                required: "Alamat wajib diisi",
                minlength: "Alamat minimal 5 karakter",
                maxlength: "Alamat maksimal 255 karakter"
            }
        }
    });
  })
</script>
@endpush
