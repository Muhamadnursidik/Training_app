@extends('layout.modal')

@section('title', __('Form Edit'))

@section('content')

    {{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', encrypt($data->code)], 'method' => 'put', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="form-group row p-0 mb-1">
            <label for="name" class="col-sm-3 col-form-label">{{ __('Nama') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control " name="name" id="name" value="{{ $data->name }}">
            </div>
        </div>
        <div class="form-group row p-0 mb-1">
            <label for="code" class="col-sm-3 col-form-label">{{ __('Code') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <input type="text" class="form-control " name="code" id="code" value="{{ $data->code }}" disabled readonly>
            </div>
        </div>
        <div class="form-group row p-0 mb-1">
            <label for="tipe_unit_kerja_id" class="col-sm-3 col-form-label">{{ __('Tipe') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                {{ Form::select('tipe_unit_kerja_id', $options_tipe_unit_kerja, $data->tipe_unit_kerja_id, ['id' => 'tipe_unit_kerja_id','class' => 'form-control select2', 'onchange' => "show_unit_kerja(this)"], $options_tipe_unit_kerja_attr) }}
                {!! Form::hidden('tipe_unit_kerja_code', null) !!}
                <div id="error_tipe_unit_kerja_id"></div>
            </div>
        </div>
        <div class="form-group row p-0 mb-1 unit" style="display: none;">
            <label for="tipe_unit_kerja" class="col-sm-3 col-form-label">{{ __('Sub Unit Kerja') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-9">
                <div class="mt-0">
                    <div class="btn-group btn-group-sm mb-1" role="Table row actions">
                        <button type="button" class="btn btn-xs btn-success" id="selected-all">Pilih Semua</button>
                    </div>
                </div>
                {{ Form::select('unit_kerja[]', $options_unit_kerja, $data->konsolidasiunit()->pluck('unit_kerja_code'), ['multiple' => true,'class' => 'select2subunitkerja']) }}
                <div id="error_unit_kerja"></div>
            </div>
        </div>
        <div class="form-group row p-0 mb-1">
            <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
            <div class="col-sm-9">
                <div class="form-check form-switch mb-1 mt-2">
                    <input type="checkbox" id="" name="status" value="1" class="form-check-input"
                        {{ $data->status == '1' ? 'checked="checked"' : '' }} data-text-on="{{ __('Aktif') }}"
                        data-text-off="{{ __('Tidak Aktif') }}">
                    <label class="custom-control-label text-muted" for="status"></label>
                </div>
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
    _data_code = {!! json_encode($options_tipe_unit_kerja_attr) !!}
    function show_unit_kerja(el){
        let code = _data_code[$('option:selected', el).val()]['data-code'];
        var includeCode = ['KC','KON','KCB','K']
        
        if(includeCode.includes(code)){
            $('.unit').show()
            $('input[name="tipe_unit_kerja_code"]').val(code)
        }else{
            $('.unit').hide()
            $('input[name="tipe_unit_kerja_code"]').val('')
        }
    }

    $("#selected-all").click(function(){
        console.log("all",$(".select2subunitkerja"));
        $(".select2subunitkerja > option").prop("selected","selected");
        $(".select2subunitkerja").trigger("change");
    });

  $(function(){
      initPage();
      $('select[name="tipe_unit_kerja_id"]').trigger('change')

    $('.select2subunitkerja').select2({
        width: '100%',
        dropdownParent: $('#modal-xl'),
        placeholder: 'Pilih Unit Kerja',
        allowClear: true
    });

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
