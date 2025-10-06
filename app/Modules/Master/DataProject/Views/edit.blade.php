@extends('layout.modal')

@section('title', __('Form Edit Data Project'))

@section('content')
    {{ Form::open([
        'id' => 'my-form',
        'route' => [$module . '.update', encrypt($data->id)],
        'method' => 'put',
        'autocomplete' => 'off'
    ]) }}
    <div class="modal-body pb-2">
        <!-- Mitra -->
        <div class="form-group row p-0 mb-1">
            <label for="mitra_id" class="col-sm-3 col-form-label">
                {{ __('Mitra') }}<sup class="text-danger">*</sup>
            </label>
            <div class="col-sm-9">
                {{ Form::select('mitra_id', $options_mitra ?? [], $data->mitra_id, [
                    'id' => 'mitra_id',
                    'class' => 'form-control select2',
                    'required' => true,
                    'placeholder' => 'Pilih Mitra'
                ]) }}
            </div>
        </div>

        <!-- Kode Project -->
        <div class="form-group row p-0 mb-1">
            <label for="kode_project" class="col-sm-3 col-form-label">
                {{ __('Kode Project') }}<sup class="text-danger">*</sup>
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="kode_project"
                       id="kode_project" value="{{ $data->kode_project }}" required>
            </div>
        </div>

        <!-- Nama Project -->
        <div class="form-group row p-0 mb-1">
            <label for="nama_project" class="col-sm-3 col-form-label">
                {{ __('Nama Project') }}<sup class="text-danger">*</sup>
            </label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="nama_project"
                       id="nama_project" value="{{ $data->nama_project }}" required>
            </div>
        </div>

        <!-- Tanggal Mulai -->
        <div class="form-group row p-0 mb-1">
            <label for="tanggal_mulai" class="col-sm-3 col-form-label">
                {{ __('Tanggal Mulai') }}<sup class="text-danger">*</sup>
            </label>
            <div class="col-sm-9">
                <input type="date" class="form-control" name="tanggal_mulai"
                       id="tanggal_mulai"
                       value="{{ $data->tanggal_mulai ? $data->tanggal_mulai->format('Y-m-d') : '' }}"
                       required>
            </div>
        </div>

        <!-- Tanggal Akhir -->
        <div class="form-group row p-0 mb-1">
            <label for="tanggal_akhir" class="col-sm-3 col-form-label">
                {{ __('Tanggal Akhir') }}<sup class="text-danger">*</sup>
            </label>
            <div class="col-sm-9">
                <input type="date" class="form-control" name="tanggal_akhir"
                       id="tanggal_akhir"
                       value="{{ $data->tanggal_akhir ? $data->tanggal_akhir->format('Y-m-d') : '' }}"
                       required>
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

    // Initialize select2
    $('.select2').select2({
      dropdownParent: $('.modal')
    });

    // Ajax form submit
    $('form#my-form').submit(function(e){
      e.preventDefault();
      $(this).myAjax({
          waitMe: '.modal-content',
          success: function (data) {
              $('.modal').modal('hide');
              if (typeof oTable !== 'undefined') {
                  oTable.reload();
              }
          }
      }).submit();
    });
  })
</script>
@endpush
