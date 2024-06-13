@extends('layout.app')

@push('plugin-styles')
    <link href="{{asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .datepicker table{
            width: 100%;
        }
    </style>
@endpush

@section('content')
@include('layout.partials.breadcrumb',compact('breadcrumb'))
    <!-- Default Light Table -->
    <div class="row">
        <div class="col">
            <div class="card card-small mb-1">
                <div class="card-header border-bottom py-2">
                    @include('components.tools-filter-with-report', ['table_id' => '#main-table'])
                </div>
                <div class="card-body pb-2 pt-2">
                    <div class="row">
                        <div class="col">
                            {{ Form::open(['id' => 'form-filter', 'autocomplete' => 'off']) }}
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="code" class="col-sm-3 col-form-label">{{ __('Aktivitas') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control"
                                                        name="keyword" id="keyword">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="name" class="col-sm-3 col-form-label">{{ __('Tanggal') }}</label>
                                                <div class="col-sm-9">
                                                    {!! Form::text('tanggal', Carbon\Carbon::now()->format('d-m-Y'), [
                                                        'class' => 'form-control  datepicker',
                                                        'readonly' => true,
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="name" class="col-sm-3 col-form-label">{{ __('User') }}</label>
                                                <div class="col-sm-9">
                                                    {{ Form::select('user', $options_user, '', ['class' => 'form-select form-select-sm select2', 'id' => 'user', 'style' => 'width:100%']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100 btn-filter"> 
                                        <i class="bx bx-filter bx-xs align-middle"></i>
                                        Filters
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card card-small mb-4">
                <div class="card-body">
                    @include('components.datatables', [
                        'id' => 'main-table',
                        'form_filter' => '#form-filter',
                        'header' => ['User', 'Username','Tabel Terkait', 'Aktivitas', 'Tgl Aktivitas', 'IP Address', 'OS', 'Browser'],
                        'data_source' => route('tools.tools.activitylog.data'),
                        'column_aksi' => 'Detail Aktifitas',
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
@include('assets.datatables')

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
@endpush

@push('plugin-scripts')
    <script type="text/javascript">
        var oTable = $('#main-table').myDataTable({
            buttons: [],
            actions: [{
                url: '{{ route('tools.tools.activitylog.detail', ['id' => '__grid_doc__']) }}',
                className: 'btn btn-warning p-1 btn-detail',
                icon: '<i class="bx bx-show bx-xs" data-feather="info" aria-hidden="true"></i>',
                title: 'Detail log',
                toggle: 'modal',
                modal: '#modal-lg'
            }, ],
            columns: [
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'user.username',
                    name: 'user.username'
                },
                {
                    data: 'tabel_terkait', 
                    name:'tabel_terkait'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'properties.ip_address',
                    name: 'properties.ip_address'
                },
                {
                    data: 'properties.os',
                    name: 'properties.os'
                },
                {
                    data: 'properties.browser',
                    name: 'properties.browser'
                },
                {
                    data: 'action',
                    className: 'text-center'
                }
            ],
            onDraw: function() {
                initModalAjax('.btn-detail');
                initDatatableAction($(this), function() {
                    oTable.reload();
                });
            },
            onComplete: function() {
                // initModalAjax();
            },
            customRow: function(row, data) {}
        });

        $(function() {
            initPage();
            initDatatableTools($('#main-table'), oTable);
            $('.select2').select2()

            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                orientation: 'bottom',
                endDate: "today"
            });
        })

        $('.export-word').hide();

        $('.export-excel, .export-pdf').on('click', function(e) {
            let elexport = $(this).attr('id');
            let keyword = $('input[name="keyword"]').val();
            let tanggal = $('input[name="tanggal"]').val();
            let user = $('select[name="user"]').val();
            let url = "{{ route('tools.' . $module . '.export') }}?type=" + elexport + "&keyword=" +
                keyword + "&tanggal=" + tanggal + "&user=" + user;
            window.open(url, '_blank');
        });
    </script>
@endpush
