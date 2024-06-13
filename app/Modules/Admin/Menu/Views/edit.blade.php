@extends('layout.modal')

@section('title', __('Form Edit'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
    <input type="hidden" name="parent_id" value="{{ $data->parent_id ? encrypt($data->parent_id) : '' }}">
    <div class="modal-body pb-2">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row mb-1">
                    <label for="name" class="col-sm-3 col-form-label">{{ __('Label') }}<sup
                            class="text-danger">*</sup></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" id="name"
                            value="{{ $data->name }}">
                    </div>
                </div>

                <div class="form-group row mb-1">
                    <label for="icon" class="col-sm-3 col-form-label">{{ __('Icon') }}</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="icon" id="icon"
                            value="{{ $data->icon }}">
                    </div>
                </div>

                <div class="form-group row mb-1">
                    <label for="description" class="col-sm-3 col-form-label">{{ __('Keterangan') }}</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="description" id="description">{{ $data->description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row mb-1">
                    <label class="col-sm-4 col-form-label">{{ __('Custom URL') }}</label>
                    <div class="col-sm-8">
                        <div
                            class="cb-dynamic-label custom-control custom-toggle custom-toggle-sm mb-1 mt-1 form-check form-switch form-check form-check-inline">
                            <input {{ $data->custom_url ? 'checked="checked"' : '' }} type="checkbox" id="custom_url"
                                name="custom_url" value="1" class="custom-control-input form-check-input"
                                data-text-on="{{ __('Ya') }}" data-text-off="{{ __('Tidak') }}">
                            <label class="custom-control-label text-muted" for="custom_url"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group row mb-1 box-routes">
                    <label for="route" class="col-sm-4 col-form-label">{{ __('Route') }}<sup
                            class="text-danger">*</sup></label>
                    <div class="col-sm-8">
                        {{ Form::select('route', $routes, $data->url, ['class' => 'form-control select2route']) }}
                    </div>
                </div>

                <div class="form-group row mb-1 box-custom">
                    <label for="url" class="col-sm-4 col-form-label">{{ __('URL') }}<sup
                            class="text-danger">*</sup></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="url" id="url"
                            value="{{ $data->url }}">
                    </div>
                </div>

                <div class="form-group row mb-1 d-none">
                    <label class="col-sm-4 col-form-label">{{ __('Otoritas Data') }}</label>
                    <div class="col-sm-8">
                        <div
                            class="cb-dynamic-label custom-control custom-toggle custom-toggle-sm mb-1 mt-1 form-check form-switch form-check form-check-inline">
                            <input {{ $data->data_authority ? 'checked="checked"' : '' }} type="checkbox"
                                id="data_authority" name="data_authority" value="1"
                                class="custom-control-input form-check-input" data-text-on="{{ __('Ada') }}"
                                data-text-off="{{ __('Tidak Ada') }}">
                            <label class="custom-control-label text-muted" for="data_authority"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin' . DIRECTORY_SEPARATOR . 'menu::feature')
        {{-- @include('admin' . DIRECTORY_SEPARATOR . 'menu::hakakses') --}}
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

@push('plugin-scripts')
    <script type="text/javascript">
        function box(s) {
            if (s) {
                $('.box-routes').hide();
                $('.box-custom').show();
            } else {
                $('.box-routes').show();
                $('.box-custom').hide();
            }
        }

        function initSelect2() {
            $.each($('.select2'), function(i, v) {
                $(v).select2({
                    dropdownParent: $('#modal-lg')
                })
            });
        }

        $(function() {
            initPage();
            // initSelect2();
            $('.select2route').select2({
                dropdownParent: $('#modal-lg')
            })

            $('form#my-form').submit(function(e) {
                e.preventDefault();
                $(this).myAjax({
                    waitMe: '.modal-content',
                    success: function(data) {
                        $('.modal').modal('hide');
                        $('#nestable-menu').nestable('destroy');
                        dd_load();
                    },
                    error: function(data) {
                        set_validation_message(data);
                    }
                }).submit();
            });

            box($('#custom_url').is(':checked'));

            $('#custom_url').change(function() {
                $('[name=route]').val('');
                $('[name=url]').val('');

                box($(this).is(':checked'));
            });
        })
    </script>
@endpush
