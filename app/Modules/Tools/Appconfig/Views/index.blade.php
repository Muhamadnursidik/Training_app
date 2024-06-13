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
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm filter-select" name="code" id="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label">{{ __('Status') }}</label>
                                        <div class="col-sm-9">
                                            <select name="status" class="select2 filter-select" data-search="false">
                                                <option value="">{{ __('Semua') }}</option>
                                                <option value="1">{{ __('Aktif') }}</option>
                                                <option value="0">{{ __('Tidak Aktif') }}</option>
                                            </select>
                                        </div>
                                    </div>
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
                    'header' => ['Code', 'Status'],
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
<script src="{{ asset(mix('assets/plugins/handlebars/handlebars.min.js')) }}"></script>
<script type="text/javascript">
    var count  = {{ $count }};
    var oTable = $('#main-table').myDataTable({
        buttons: [
            {
                id: 'add',
                className: 'btn btn-primary btn-sm btn-create',
                url: '{{ route($module . ".create") }}',
                modal: '#modal-xl'
            }
        ],
        actions: [
            {
                id : 'edit',
                url: '{{ route($module . '.edit', ['appconfig' => '__grid_doc__']) }}',
                className: 'btn btn-primary btn-sm btn-edit',
                modal: '#modal-xl'
            },
        ],
        columns: [
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
            initModalAjax('.btn-create');
            // if(count){
            //     $('.tambah').remove();
            // }
        },
        customRow: function(row, data) {
            if (data.status == '1'){
                $('td:eq(1)', row).html( __('Aktif') ).addClass('text-success');
            } else {
                $('td:eq(1)', row).html( __('Tidak Aktif') ).addClass('text-danger');
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
