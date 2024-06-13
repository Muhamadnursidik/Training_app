@extends('layout.app')

@section('content')
@include('layout.partials.breadcrumb',compact('breadcrumb'))
    <!-- Default Light Table -->
    <div class="row inbox-wrapper">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">


                        <div class="col-lg-3 border-right-lg">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="order-first">
                                    <h4>Notifikasi</h4>
                                    <p class="text-muted">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-12">

                                    {{ Form::open(['id' => 'form-filter', 'autocomplete' => 'off']) }}
                                    <div class="p-3 border-bottom">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6">
                                                <div class="d-flex align-items-end mb-2 mb-md-0">
                                                    <h4 class="me-1">Inbox</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-group">
                                                    <input class="form-control " type="text"
                                                        name="keyword" placeholder="Search ...">
                                                    <button class="btn btn-light btn-icon filter-click" type="button"
                                                        id="button-search-addon">
                                                        <i class="bx bx-search-alt-2 bx-xs" data-feather="search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-1">
                                    @include('components.datatables', [
                                        'id' => 'main-table',
                                        'form_filter' => '#form-filter',
                                        'header' => ['Pesan', 'Tgl Pesan'],
                                        'data_source' => route('mapping.notification.listData'),
                                        'delete_action' => route('mapping.notification.destroys'),
                                    ])
                                </div>
                            </div>
                            <!-- End Default Light Table -->
                        </div>
                    </div>
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
            buttons: [],
            filter: false,
            lengthChange: false,
            actions: [{
                id: 'delete',
                url: '{{ route('mapping.notification.destroy', ['id' => '__grid_doc__']) }}'
            }],
            columns: [{
                    data: 'checkbox'
                },
                {
                    data: 'msg',
                    name: 'msg'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    className: 'text-center'
                }
            ],
            onDraw: function() {
                initModalAjax('[data-bs-toggle="modal-edit"]');
                initDatatableAction($(this), function() {
                    oTable.reload();
                });
            },
            onComplete: function() {
                initModalAjax();
            },
            customRow: function(row, data) {}
        });

        $(function() {
            initPage();

            $('#button-search-addon').on('click', (e) => {
                oTable.reload();
            })

            $(window).keydown(function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        })
    </script>
@endpush
