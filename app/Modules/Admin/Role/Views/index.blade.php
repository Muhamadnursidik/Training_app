@extends('layout.app')

@push('style')
    
@endpush
@section('content')
    @include('layout.partials.breadcrumb', compact('breadcrumb'))
    <!-- Default Light Table -->
    <div class="row">
        <div class="col">
            <div class="card card-small mb-1">
                <div class="card-header border-bottom pt-1 pb-1">
                    @include('components.tools-filter', ['table_id' => '#main-table'])
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            {{ Form::open(['id' => 'form-filter', 'autocomplete' => 'off']) }}
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-6 mb-1">
                                            <div class="form-group row">
                                                <label for="name"
                                                    class="col-sm-3 col-form-label">{{ __('Nama') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="name"
                                                        id="name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-1">
                                            <div class="form-group row">
                                                <label for="name"
                                                    class="col-sm-3 col-form-label">{{ __('Status') }}</label>
                                                <div class="col-sm-9">
                                                    <select name="status" class="select2" data-search="false">
                                                        <option value="">{{ __('Semua') }}</option>
                                                        <option value="1">{{ __('Aktif') }}</option>
                                                        <option value="0">{{ __('Tidak Aktif') }}</option>
                                                    </select>
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
                        'header' => ['Nama', 'Keterangan', 'Status'],
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
    <script src="{{ asset('assets/plugins/handlebars/handlebars.min.js') }}"></script>

    <script type="text/javascript">
        var oTable = $('#main-table').myDataTable({
            buttons: [{
                id: 'add',
                url: '{{ route($module . '.create') }}',
                modal: false,
                className: 'btn btn-primary btn-create'
            }],
            actions: [{
                    id: 'edit',
                    url: '{{ route($module . '.edit', ['role' => '__grid_doc__']) }}',
                    modal: false,
                },
                {
                    id: 'delete',
                    url: '{{ route($module . '.destroy', ['id' => '__grid_doc__']) }}'
                },
                {
                    id: 'duplicate',
                    url: '{{ route($module . '.duplicate', ['id' => '__grid_doc__']) }}',
                    className: 'btn btn-warning btn-sm p-1 btn-duplicate',
                    title: 'Duplikat Role',
                    icon: '<i class="bx bx-copy bx-xs"></i>'
                }
            ],
            columns: [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    className: 'text-center'
                }
            ],
            onDraw: function() {
                initModalAjax('.btn-mapping');
                initDatatableAction($(this), function() {
                    oTable.reload();
                });

                initCustomAction($(this));
            },
            onComplete: function() {
                // initModalAjax('.btn-create');
            },
            customRow: function(row, data) {
                if (data.status == '1') {
                    $('td:eq(2)', row).html(__('Aktif')).addClass('text-success');
                } else {
                    $('td:eq(2)', row).html(__('Tidak Aktif')).addClass('text-danger');
                }
            }
        });

        function initCustomAction(table) {
            $('.btn-duplicate', table).on('click', function(e) {
                e.preventDefault();

                $(this).myAjax({
                    url: $(this).attr('href'),
                    type: 'GET',
                    waitMe: '.page-wrapper',
                    success: function(data) {
                        oTable.reload()
                    }
                }).submit({
                    confirm: {
                        text: 'Akan menduplikasi data role ?'
                    },
                    success: {
                        text: 'Role barhasil diduplikasi'
                    }
                });
            });
        }

        $(function() {
            initPage();
            initDatatableTools($('#main-table'), oTable);
        })
    </script>
@endpush
