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
                                                <label for="kode_mitra" class="col-sm-3 col-form-label">{{ __('Kode Mitra') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="kode_mitra" id="kode_mitra">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="nama_mitra" class="col-sm-3 col-form-label">{{ __('Nama Mitra') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="nama_mitra" id="nama_mitra">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="alamat" class="col-sm-3 col-form-label">{{ __('Alamat') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="alamat" id="alamat">
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
                        'header' => ['Kode Mitra', 'Nama Mitra', 'Alamat'],
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
                url: '{{ route($module . ".create") }}',
                modal: '#modal-xl',
                className: 'btn btn-primary btn-add',
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
                url: '{{ route($module . ".edit", ["datamitra" => "__grid_doc__"]) }}',
                modal: '#modal-xl',
                className: "btn btn-light p-1 pb-1 btn-edit"
            },
            {
                id: 'delete',
                url: '{{ route($module . ".destroy", ["id" => "__grid_doc__"]) }}'
            },
            {
                id: 'restore',
                title: 'Restore Deleted',
                url: '{{ route($module . ".restore", ["id" => "__grid_doc__"]) }}',
                className: 'btn btn-xs btn-outline-success btn-restore p-1 pb-1',
                icon: '<i class="bx bx-rotate-left bx-xs"></i>',
            }
        ],
        columns: [
            { data: 'kode_mitra', name: 'kode_mitra' },
            { data: 'nama_mitra', name: 'nama_mitra' },
            { data: 'alamat', name: 'alamat' },
            { data: 'id', name: 'actions', orderable: false, searchable: false }
        ],
        customRow: function(row, data) {
            var $aksi = $('td:last', row);
            $aksi.find('.btn-restore').hide();

            if (data.deleted_at != null) {
                // Alternatif: ubah style row menjadi abu-abu atau strikethrough
                $(row).addClass('text-muted');
                // atau
                // $('td:eq(1)', row).css('text-decoration', 'line-through');

                $aksi.find('.btn-edit').hide();
                $aksi.find('.btn-delete').hide();
                $aksi.find('.btn-restore').show();
            }
        },
        onDraw: function() {
            initModalAjax('.btn-edit');
            initDatatableAction($(this), function() {
                oTable.reload();
            });
        },
        onComplete: function() {
            var _import = '{{ auth()->user()->can($module . ".import") }}';
            if (_import != '1') {
                $('.btn-import').remove()
            }
            initModalAjax('.btn-add, .btn-import');
        }
    });

    // Export functionality with filters
    $(document).on('click', '.export-btn', function(e) {
        e.preventDefault();

        var format = $(this).data('format');
        var filters = $('#form-filter').serialize();

        var exportUrls = {
            'pdf': '{{ route("master.datamitra.export.pdf") }}',
            'excel': '{{ route("master.datamitra.export.excel") }}',
            'word': '{{ route("master.datamitra.export.word") }}'
        };

        var url = exportUrls[format];
        if (filters) {
            url += '?' + filters;
        }

        // Open in new window/tab
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
