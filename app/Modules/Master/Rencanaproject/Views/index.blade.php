@extends('layout.app')

@section('content')
@include('layout.partials.breadcrumb', compact('breadcrumb'))

<!-- Filter Section -->
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
                            @push('tools-filter')
                                <a href="{{ route($module . '.export', ['type' => 'pdf']) }}" class="dropdown-item"><i class="bx bx-file bx-xs text-danger"></i> Export PDF</a>
                                <a href="{{ route($module . '.export', ['type' => 'excel']) }}" class="dropdown-item"><i class="bx bx-spreadsheet bx-xs text-success"></i> Export Excel</a>
                                <a href="{{ route($module . '.export', ['type' => 'word']) }}" class="dropdown-item"><i class="bx bx-file bx-xs text-info"></i> Export Word</a>
                            @endpush
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row">
                                        <!-- Kode Project -->
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="kode_project" class="col-sm-3 col-form-label">{{ __('Project') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="kode_project" id="kode_project" class="form-control" placeholder="Masukkan kode project">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Aktivitas -->
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="aktivitas" class="col-sm-3 col-form-label">{{ __('Aktivitas') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="aktivitas" id="aktivitas" class="form-control" placeholder="Cari aktivitas...">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Level -->
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="level" class="col-sm-3 col-form-label">{{ __('Level') }}</label>
                                                <div class="col-sm-9">
                                                    <select name="level" id="level" class="form-control">
                                                        <option value="">{{ __('- Semua Level -') }}</option>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <option value="{{ $i }}">{{ __('Level') }} {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Minggu Ke -->
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="minggu_ke" class="col-sm-3 col-form-label">{{ __('Minggu Ke') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="minggu_ke" id="minggu_ke" class="form-control" min="1" max="53" placeholder="1-53">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Range Tanggal -->
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="tanggal_mulai" class="col-sm-3 col-form-label">{{ __('Tanggal Mulai') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="tanggal_akhir" class="col-sm-3 col-form-label">{{ __('Tanggal Akhir') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Filter -->
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100 btn-filter">
                                        <i class="bx bx-filter bx-xs align-middle"></i> Filter
                                    </button>
                                    <button type="button" class="btn btn-secondary w-100 mt-1" onclick="resetFilter()">
                                        <i class="bx bx-refresh bx-xs align-middle"></i> Reset
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

<!-- Data Table -->
<div class="row">
    <div class="col">
        <div class="card card-small mb-4">
            <div class="card-body">
                @include('components.datatables', [
                    'id' => 'main-table',
                    'form_filter' => '#form-filter',
                    'header' => [
                        'Project',
                        'Aktivitas', 
                        'Level',
                        'Parent',
                        'Bobot (%)',
                        'Tanggal Mulai',
                        'Tanggal Akhir',
                        'Minggu Ke'
                    ],
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
                modal: '#modal-md',
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
                url: '{{ route($module . ".edit", ["rencanaproject" => "__grid_doc__"]) }}',
                modal: '#modal-md',
                className: "btn btn-light p-1 pb-1 btn-edit"
            },
            {
                id: 'delete',
                url: '{{ route($module . ".destroy", ["id" => "__grid_doc__"]) }}',
                method: 'delete',
            },
            {
                id: 'restore',
                url: '{{ route($module . ".restore", ["id" => "__grid_doc__"]) }}',
                className: 'btn btn-xs btn-outline-success btn-restore p-1 pb-1',
                icon: '<i class="bx bx-rotate-left bx-xs"></i>',
            }
        ],
        columns: [
            {data: 'kode_project', name: 'kode_project'},
            {data: 'aktivitas', name: 'aktivitas', className: 'text-center'},
            {data: 'level', name: 'level'},
            {data: 'parent.aktivitas', name: 'parent.aktivitas'},
            {data: 'bobot', name: 'bobot'},
            {data: 'tanggal_mulai', name: 'tanggal_mulai'},
            {data: 'tanggal_akhir', name: 'tanggal_akhir'},
            {data: 'minggu_ke', name: 'minggu_ke'},
            {data: 'action', className: 'text-center', orderable: false, searchable: false},
        ],
        onDraw: function() {
            initModalAjax('.btn-edit');
            initDatatableAction($(this), function(){
                oTable.reload();
            });
        },
        onComplete: function() {
            var _import = '{{ auth()->user()->can($module.".import") }}';
            if(_import != '1'){
                $('.btn-import').remove()
            }
            initModalAjax('.btn-add, .btn-import'); 
        },
        customRow: function(row, data) {
            $('td:eq(8)', row).find('.btn-restore').hide();
            if (data.deleted_at != null) {
                $('td:eq(8)', row).find('.btn-edit').hide();
                $('td:eq(8)', row).find('.btn-delete').hide();
                $('td:eq(8)', row).find('.btn-restore').show();
            }
        }
    });
</script>

<script type="text/javascript">
    $(function(){
        initPage();
        initDatatableTools($('#main-table'), oTable);
    })
</script>
@endpush