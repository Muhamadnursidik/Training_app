@extends('layout.app')

@section('content')
@include('layout.partials.breadcrumb',compact('breadcrumb'))
<!-- Default Light Table -->
<div class="row">
    <div class="col">
        <div class="card card-small mb-1">
            <div class="card-header border-bottom pb-1 pt-2">
                @include('components.tools-filter', ['table_id' => '#main-table' ])
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
                                                <label for="name" class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="name" id="name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="name" class="col-sm-3 col-form-label">{{ __('Status') }}</label>
                                                <div class="col-sm-9">
                                                    <select name="status" class="select2" data-search="false">
                                                        <option value="">{{ __('Semua') }}</option>
                                                        <option value="1">{{ __('Aktif') }}</option>
                                                        <option value="0">{{ __('Tidak Aktif') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="tipe_unit_kerja_id_filter" class="col-sm-3 col-form-label">{{ __('Tipe') }}</label>
                                                <div class="col-sm-9">
                                                    {{ Form::select('tipe_unit_kerja_id', $options_tipe_unit_kerja, null, ['class' => 'select2','id' => 'tipe_unit_kerja_id_filter']) }}
                                                    <div id="error_tipe_unit_kerja_id"></div>
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
<div class="col-md-12 text-danger alert alert-danger">
    Kode Unit Kerja tidak dapat diubah. Apabila ada penyesuaian kode maka diperlukan tambah data dan pemetaan yang lainnya.
</div>
<div class="row">
    <div class="col">
        <div class="card card-small mb-4">
            <div class="card-body">
                @include('components.datatables', [
                    'id' => 'main-table',
                    'form_filter' => '#form-filter',
                    'header' => ['Nama', 'Code', 'Status'],
                    'data_source' => route($module . '.data'),
                ])
            </div>
        </div>
    </div>
</div>
<!-- End Default Light Table -->

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
                id : 'edit',
                url: '{{ route($module . '.edit', ['unitkerja' => '__grid_doc__']) }}',
                modal: '#modal-xl',
                className: "btn btn-light p-1 pb-1 btn-edit"
            },
            {
                id : 'delete',
                url: '{{ route($module . '.destroy', ['id' => '__grid_doc__']) }}'
            },
            {
                id: 'restore',
                title: 'Restore Deleted',
                url: '{{ route($module . '.restore', ['id' => '__grid_doc__']) }}',
                className: 'btn btn-xs btn-outline-success btn-restore p-1 pb-1',
                icon: '<i class="bx bx-rotate-left bx-xs"></i>',
            }
        ],
        columns: [
            {data: 'name', name:'name'},
            {data: 'code', name:'code'},
            {data: 'status', name:'status'},
            {data: 'action', className: 'text-center'}
        ],
        onDraw : function() {
            initModalAjax('.btn-edit');
            initDatatableAction($(this), function(){
              oTable.reload();
            });
        },
        onComplete: function() {
            var _import = '{{ auth()->user()->can($module.'.import') }}';
            if(_import != '1'){
                $('.btn-import').remove()
            }
            initModalAjax('.btn-add, .btn-import');
        },
        customRow: function(row, data) {
            $('td:eq(3)', row).find('.btn-restore').hide();
            if (data.status == '1'){
                $('td:eq(2)', row).html('<span class="badge bg-success-subtle text-success text-uppercase">' + __('Aktif') + '</span>');
            } else {
                if (data.deleted_at != null) {
                    $('td:eq(2)', row).html('<span class="badge bg-danger-subtle text-danger text-uppercase">' + __('Deleted') + '</span>');
                    $('td:eq(3)', row).find('.btn-edit').hide();
                    $('td:eq(3)', row).find('.btn-delete').hide();
                    $('td:eq(3)', row).find('.btn-restore').show();
                } else {
                    $('td:eq(2)', row).html('<span class="badge bg-danger-subtle text-danger text-uppercase">' + __('Tidak Aktif') + '</span>');
                }
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
