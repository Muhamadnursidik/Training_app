@extends('layout.app')

@section('content')
    @include('layout.partials.breadcrumb', compact('breadcrumb'))

    <!-- Filter -->
    <div class="row">
        <div class="col">
            <div class="card card-small mb-1">
                <div class="card-header border-bottom pb-1 pt-2">
                    @include('components.tools-filter', ['table_id' => '#main-table'])
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            {{ Form::open(['id' => 'form-filter', 'autocomplete' => 'off']) }}
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="kode_project" class="col-sm-3 col-form-label">{{ __('Kode Project') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="kode_project" id="kode_project">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="nama_project" class="col-sm-3 col-form-label">{{ __('Nama Project') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="nama_project" id="nama_project">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="mitra_id" class="col-sm-3 col-form-label">{{ __('Kode Mitra') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="mitra_id" id="mitra_id">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="tanggal_mulai" class="col-sm-3 col-form-label">{{ __('Tanggal Mulai') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="tanggal_akhir" class="col-sm-3 col-form-label">{{ __('Tanggal Akhir') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" name="tanggal_akhir" id="tanggal_akhir">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100 btn-filter">
                                        <i class="bx bx-filter bx-xs align-middle"></i> Filters
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

    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card card-small mb-4">
                <div class="card-body">
                    @include('components.datatables', [
                        'id' => 'main-table',
                        'form_filter' => '#form-filter',
                        'header' => ['Kode Project', 'Nama Project', 'Kode Mitra', 'Tanggal Mulai', 'Tanggal Akhir'],
                        'data_source' => route($module . '.data'),
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection

@include('assets.datatables')

@push('plugin-scripts')
<script type="text/javascript">
    var oTable = $('#main-table').myDataTable({
        buttons: [
            {
                id: 'add',
                title: 'Tambah',
                url: '{{ route($module . ".create") }}',
                modal: '#modal-xl',
                className: 'btn btn-primary btn-add',
                icon: '<i class="bx bx-plus"></i>'
            },
            {
                id: 'import',
                title: 'Import Data',
                url: '{{ route($module . ".import") }}',
                modal: '#modal-md',
                className: 'btn btn-warning btn-import ms-2',
                icon: '<i data-feather="upload" class="feather-16"></i>',
                toggle: 'modal'
            }
        ],
        actions: [
            {
                id: 'edit',
                url: '{{ route($module . ".edit", ["dataproject" => "__grid_doc__"]) }}',
                modal: '#modal-xl',
                className: "btn btn-light p-1 pb-1 btn-edit"
            },
            {
                id: 'delete',
                url: '{{ route($module . ".destroy", ["id" => "__grid_doc__"]) }}'
            }
        ],
        columns: [
            { data: 'kode_project', name: 'kode_project' },
            { data: 'nama_project', name: 'nama_project' },
            { data: 'kode_mitra_display', name: 'mitra_id' },
            { data: 'tanggal_mulai', name: 'tanggal_mulai' },
            { data: 'tanggal_akhir', name: 'tanggal_akhir' },
            { data: 'id', name: 'actions', orderable: false, searchable: false }
        ],
        customRow: function(row, data) {
            if (data.deleted_at != null) {
                $(row).addClass('text-muted');
            }
        },
        onDraw: function() {
            initModalAjax('.btn-edit');
            initDatatableAction($(this), function() {
                oTable.reload();
            });
        },
        onComplete: function() {
            initModalAjax('.btn-add, .btn-import');
        }
    });

    // Export functionality with filters
    $(document).on('click', '.export-btn', function(e) {
        e.preventDefault();

        var format = $(this).data('format');
        var filters = $('#form-filter').serialize();

        var exportUrls = {
            'pdf': '{{ route("master.dataproject.export.pdf") }}',
            'excel': '{{ route("master.dataproject.export.excel") }}',
            'word': '{{ route("master.dataproject.export.word") }}'
        };

        var url = exportUrls[format];
        if (filters) {
            url += '?' + filters;
        }

        window.open(url, '_blank');
    });
</script>

<script type="text/javascript">
    $(function() {
        initPage();
        initDatatableTools($('#main-table'), oTable);
    });
</script>
@endpush
