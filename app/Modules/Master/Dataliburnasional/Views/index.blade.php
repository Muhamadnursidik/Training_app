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
                                                <label for="tanggal" class="col-sm-3 col-form-label">{{ __('Tanggal') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" name="tanggal" id="tanggal">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="keterangan" class="col-sm-3 col-form-label">{{ __('Keterangan') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="keterangan" id="keterangan">
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
                    'header' => ['Tanggal', 'Keterangan'],
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
                id : 'edit',
                url: '{{ route($module . '.edit', ['dataliburnasional' => '__grid_doc__']) }}',
                modal: '#modal-md',
                className: "btn btn-light p-1 pb-1 btn-edit"
            },
            {
                id : 'delete',
                url: '{{ route($module . '.destroy', ['id' => '__grid_doc__']) }}',
                method: 'destroy',
            }
        ],
        columns: [
            {data: 'tanggal', name:'tanggal'},
            {data: 'keterangan', name:'keterangan'},
            {data: 'action', className: 'text-center'},
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
