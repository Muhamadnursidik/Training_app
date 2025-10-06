@extends('layout.modal')

@section('title', __('Form Tambah Data Project'))

@section('content')
    {{ Form::open(['id' => 'form-project', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <!-- Mitra -->
        <div class="form-group row p-0 mb-1">
            <label for="mitra_id" class="col-sm-3 col-form-label">
                {{ __('Mitra') }}<sup class="text-danger">*</sup>
            </label>
            <div class="col-sm-9">
                <select name="mitra_id" id="mitra_id" class="form-control" required>
                    <option value="">Kode Mitra</option>
                    @foreach($options_mitra ?? [] as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>  
        </div>

        <!-- Kode Project -->
        <div class="form-group row p-0 mb-1">
            <label for="kode_project" class="col-sm-3 col-form-label">
                {{ __('Kode Project') }}<sup class="text-danger">*</sup>
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="kode_project" id="kode_project" required>
            </div>
        </div>

        <!-- Nama Project -->
        <div class="form-group row p-0 mb-1">
            <label for="nama_project" class="col-sm-3 col-form-label">
                {{ __('Nama Project') }}<sup class="text-danger">*</sup>
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="nama_project" id="nama_project" required>
            </div>
        </div>

        <!-- Tanggal Mulai -->
            <div class="form-group row p-0 mb-1">
            <label for="tanggal_mulai" class="col-sm-3 col-form-label">
                {{ __('Tanggal Mulai') }}<sup class="text-danger">*</sup>
            </label>
            <div class="col-sm-9">
                <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai" required>
            </div>
        </div>

        <!-- Tanggal Akhir -->
        <div class="form-group row p-0 mb-1">
            <label for="tanggal_akhir" class="col-sm-3 col-form-label">
                {{ __('Tanggal Akhir') }}<sup class="text-danger">*</sup>
            </label>
            <div class="col-sm-9">
                <input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir" required>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary" id="btn-submit">{{ __('Simpan') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

@push('plugin-scripts')
<script type="text/javascript">
  $(function(){
    initPage();

    $('#form-project').submit(function(e){
      e.preventDefault();

      $('#btn-submit').prop('disabled', true).text('Menyimpan...');
      $('.modal-content').waitMe({ effect: 'progressBar', color: '#095d2d' });  

      var formData = $(this).serialize();

      $.ajax({
          url: $(this).attr('action'),
          type: 'POST',
          data: formData,
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
              $('.modal-content').waitMe("hide");
              $('#btn-submit').prop('disabled', false).text('Simpan');

              if (response.status === true || response.status === 'success') {
                  $('.modal').modal('hide');
                  if (typeof oTable !== 'undefined') {
                      oTable.reload();  
                  }
                  Swal.fire({
                      title: 'Berhasil',
                      text: response.message || 'Data berhasil disimpan',
                      icon: 'success',
                      timer: 2000
                  });
              } else {
                  Swal.fire({
                      title: 'Error',
                      text: response.message || 'Terjadi kesalahan',
                      icon: 'error'
                  });
              }
          },
          error: function(xhr) {
              $('.modal-content').waitMe("hide");
              $('#btn-submit').prop('disabled', false).text('Simpan');

              let errorMessage = 'Terjadi kesalahan sistem';
              if (xhr.responseJSON && xhr.responseJSON.message) {
                  errorMessage = xhr.responseJSON.message;
              }

              Swal.fire({
                  title: 'Error',
                  text: errorMessage,
                  icon: 'error'
              });
          }
      });
    });
  });
</script>
@endpush