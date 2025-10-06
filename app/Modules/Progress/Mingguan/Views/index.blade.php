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

                                        <!-- Minggu Ke -->
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="minggu_ke" class="col-sm-3 col-form-label">{{ __('Minggu Ke') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="minggu_ke" id="minggu_ke" class="form-control" min="1" max="53" placeholder="1-53">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Progress (%) -->
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="progres" class="col-sm-3 col-form-label">{{ __('Progress (%)') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="number" name="progres" id="progres" class="form-control" min="0" max="100" placeholder="0-100">
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
                        'Minggu Ke',
                        'Tanggal Mulai',
                        'Tanggal Akhir',
                        'Progress (%)',
                        'Keterangan',
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
            }
        ],
        actions: [
            {
                id: 'edit',
                url: '{{ route($module . ".edit", ["mingguan" => "__grid_doc__"]) }}',
                modal: '#modal-md',
                className: "btn btn-light p-1 pb-1 btn-edit"
            },
            {
                id: 'delete',
                url: '{{ route($module . ".destroy", ["id" => "__grid_doc__"]) }}',
                method: 'delete',
            }
        ],
        columns: [
            {data: 'kode_project', name: 'kode_project'},
            {data: 'minggu_ke', name: 'minggu_ke'},
            {data: 'tanggal_mulai', name: 'tanggal_mulai'},
            {data: 'tanggal_akhir', name: 'tanggal_akhir'},
            {data: 'progres', name: 'progres'},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'action', orderable: false, searchable: false},
        ],
        onDraw: function() {
            initModalAjax('.btn-edit');
            initDatatableAction($(this), function(){
                oTable.reload();
            });
        },
        onComplete: function() {
            initModalAjax('.btn-add'); 
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
