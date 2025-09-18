@extends('layout.modal')

@section('title', __('Form Edit Rencana Project'))

@section('content')
{{ Form::model($data, ['id' => 'form-update', 'route' => [$module . '.update', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
<div class="modal-body">
    <div class="row">
        <!-- Kode Project -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="kode_project" class="form-label required">{{ __('Kode Project') }}</label>
                {!! Form::text('kode_project', null, ['class' => 'form-control', 'required', 'maxlength' => 50]) !!}
            </div>
        </div>

        <!-- Level -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="level" class="form-label">{{ __('Level') }}</label>
                {!! Form::number('level', null, ['class' => 'form-control', 'readonly']) !!}
                <small class="text-muted">Level akan otomatis disesuaikan berdasarkan parent</small>
            </div>
        </div>

        <!-- Aktivitas -->
        <div class="col-12">
            <div class="form-group mb-3">
                <label for="aktivitas" class="form-label required">{{ __('Aktivitas') }}</label>
                {!! Form::textarea('aktivitas', null, ['class' => 'form-control', 'rows' => 3, 'required', 'maxlength' => 255]) !!}
            </div>
        </div>

        <!-- Parent -->
        <div class="col-12">
            <div class="form-group mb-3">
                <label for="parent_id" class="form-label">{{ __('Parent Aktivitas') }}</label>
                <select name="parent_id" id="parent_id" class="form-control select2">
                    <option value="">{{ __('- Root Level (Tanpa Parent) -') }}</option>
                    @if(isset($parents))
                        @foreach($parents as $parent)
                            <option value="{{ $parent['id'] }}" 
                                    data-level="{{ $parent['level'] }}"
                                    {{ $data->parent_id == $parent['id'] ? 'selected' : '' }}>
                                {!! $parent['text'] !!}
                            </option>
                        @endforeach
                    @endif
                </select>
                <small class="text-muted">Pilih parent untuk membuat sub-aktivitas</small>
            </div>
        </div>

        <!-- Tanggal -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="tanggal_mulai" class="form-label required">{{ __('Tanggal Mulai') }}</label>
                {!! Form::date('tanggal_mulai', null, ['class' => 'form-control', 'required']) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="tanggal_akhir" class="form-label required">{{ __('Tanggal Akhir') }}</label>
                {!! Form::date('tanggal_akhir', null, ['class' => 'form-control', 'required']) !!}
            </div>
        </div>

        <!-- Bobot dan Minggu -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="bobot" class="form-label">{{ __('Bobot (%)') }}</label>
                {!! Form::number('bobot', null, ['class' => 'form-control', 'min' => 0, 'max' => 100, 'step' => 0.01]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="minggu_ke" class="form-label">{{ __('Minggu Ke') }}</label>
                {!! Form::number('minggu_ke', null, ['class' => 'form-control', 'min' => 1, 'max' => 53]) !!}
                <small class="text-muted">Kosongkan untuk otomatis</small>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save"></i> {{ __('Update') }}
    </button>
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