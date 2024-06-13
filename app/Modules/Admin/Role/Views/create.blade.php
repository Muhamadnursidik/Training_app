@extends('layout.app')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-2">
    <div><h5 class="mb-3 mb-md-0 fw-bold">{{ $pageSubTitle }} {{ $pageTitle }} </h5></div> 
</div>
    <!-- Default Light Table -->
    <div class="row">
        <div class="col">
            {{ Form::open(['id' => 'my-form', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off']) }}
                <div class="card card-small mb-4">
                    <div class="card-header border-bottom pb-2 pt-2">
                        <h6 class="m-0">{{ __('Form Tambah') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group row mb-1">
                                    <label for="name" class="col-sm-3 col-form-label">{{ __('Nama') }}<sup class="text-danger">*</sup></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control " name="name" id="name">
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label for="description" class="col-sm-3 col-form-label">{{ __('Keterangan') }}</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control " name="description" id="description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-1">
                                    <label class="col-sm-3 col-form-label">{{ __('Status') }}</label>
                                    <div class="col-sm-9">
                                        <div class="form-check form-switch mb-1 mt-1">
                                            <input type="checkbox" id="" name="status" value="1" class="form-check-input" checked="checked" data-text-on="{{ __('Aktif') }}" data-text-off="{{ __('Tidak Aktif') }}" >
                                            <label class="custom-control-label text-muted" for="status"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-1 mt-2">
                                    <label for="description" class="col-sm-3 col-form-label">{{ __('User Manual') }}</label>
                                    <div class="col-sm-9">
                                        <input type="file" name="files" class="form-control" accept="application/pdf">
                                        <small class="text-danger">Hanya mendukung file yang berextensi .pdf</small>
                                        <div id="error_files"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('admin'.DIRECTORY_SEPARATOR.'role::permission')
                    </div>
                    <div class="card-footer border-top">
                        <div class="row">
                            <div class="col text-end">
                                <a href="{{ route($module . '.index') }}" class="btn btn-light" >{{ __('Kembali') }}</a>
                                <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <!-- End Default Light Table -->
@endsection
@push('plugin-scripts')
<script src="{{asset('assets/plugins/handlebars/handlebars.min.js')}}"></script>
<script src="{{asset('assets/plugins/nestable2/jquery.nestable.min.js')}}"></script>    
@endpush

@push('custom-scripts')
<script type="text/javascript">
  $(function(){
    initPage();

    $('form#my-form').submit(function(e){
      e.preventDefault();
      $(this).myAjax({
          waitMe: '.page-content',
          success: function (data) {
              window.location = '{{ route($module . '.index') }}'
          },
          error: function(data) {
              set_validation_message(data);
          }
      }).submit();
    });
  })
</script>
@endpush
