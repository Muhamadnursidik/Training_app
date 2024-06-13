@extends('layout.modal')

@section('title', __('Form Mapping'))
@push('plugin-styles')
    <style>
        .select2-container--default .select2-results__option--selected{
            background-color: #a9c3ee !important;
        }
    </style>
@endpush
@section('content')
    {{ Form::open(['id' => 'mapping-form', 'route' => [$module . '.mappingstore', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        <div class="row">
            <div class="form-group row mb-1">
                <label for="name" class="col-sm-3 col-form-label">{{ __('Role') }}<sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" readonly name="name" id="name" value="{{ $data->name }}">
                </div>
            </div>
        </div>
        {{-- @include('admin' . DIRECTORY_SEPARATOR . 'menu::hakakses') --}}
        @include('admin' . DIRECTORY_SEPARATOR . 'role::mapping-data')
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
    </div>
    {!! Form::close() !!}
@endsection

@push('custom-scripts')
    <script type="text/javascript">
        $(function() {

            $('.select2').select2({
                dropdownParent: $('#modal-lg')
            });

            $('form#mapping-form').submit(function(e) {
                e.preventDefault();
                $(this).myAjax({
                    waitMe: '.modal-content',
                    success: function(data) {
                        $('.modal').modal('hide');
                    },
                    error: function(data) {
                        set_validation_message(data);
                    }
                }).submit();
            });
        })
    </script>
@endpush
