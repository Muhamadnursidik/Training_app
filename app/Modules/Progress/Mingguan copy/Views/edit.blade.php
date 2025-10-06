@extends('layout.modal')

@section('title', __('Form Edit Rencana Project'))

@section('content')
{{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
<div class="modal-body">
    <div class="mb-3">
        <label for="kode_project" class="form-label">Kode Project</label>
        <input type="text" class="form-control" id="kode_project" name="kode_project" value="{{ $data->kode_project }}" required>
    </div>
    <div class="mb-3">
        <label for="aktivitas" class="form-label">Aktivitas</label>
        <input type="text" class="form-control" id="aktivitas" name="aktivitas" value="{{ $data->aktivitas }}" required>
    </div>
    <div class="mb-3">
        <label for="level" class="form-label">Level</label>
        <input type="number" class="form-control" id="level" name="level" value="{{ $data->level }}">
    </div>
    <div class="mb-3">
        <label for="bobot" class="form-label">Bobot (%)</label>
        <input type="number" class="form-control" id="bobot" name="bobot" step="0.01" value="{{ $data->bobot }}">
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ $data->tanggal_mulai?->format('Y-m-d') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="{{ $data->tanggal_akhir?->format('Y-m-d') }}">
        </div>
    </div>
    <div class="mb-3">
        <label for="minggu_ke" class="form-label">Minggu Ke</label>
        <input type="number" class="form-control" id="minggu_ke" name="minggu_ke" value="{{ $data->minggu_ke }}">
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary">Simpan</button>
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