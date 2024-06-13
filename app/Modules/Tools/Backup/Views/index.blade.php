@extends('layout.app')

@section('content')
@include('layout.partials.breadcrumb',compact('breadcrumb'))
    <!-- Default Light Table -->
    <div class="row">
        <div class="col">
            <div class="card card-small mb-1">
                <div class="card-header border-bottom py-2">
                    @include('components.tools-filter', ['table_id' => '#main-table'])
                </div>
                <div class="card-body pb-2 pt-2">
                    <div class="row">
                        <div class="col">
                            {{ Form::open(['id' => 'form-filter', 'autocomplete' => 'off']) }}
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row mb-1">
                                                <label for="keyword" class="col-sm-2 col-form-label">{{ __('Keyword') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control"
                                                        name="name" id="keyword">
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
                <div class="card-header pt-1 pb-1">
                    <div class="btn-group btn-group-sm mb-1 mt-1" role="Table row actions">
                        <button type="button" class="btn btn-xs btn-success backup" id="backup"
                            data-action="{{ route('tools.tools.backup.run') }}">Backup Database</button>
                    </div>
                </div>
                <div class="card-body">
                    @include('components.datatables', [
                        'id' => 'main-table',
                        'form_filter' => '#form-filter',
                        'header' => ['Nama File', 'Tgl Backup'],
                        'data_source' => route('tools.tools.backup.data'),
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
            buttons: [],
            actions: [{
                    id: 'delete',
                    url: '{{ route('tools.' . $module . '.destroy', ['id' => '__grid_doc__']) }}'
                },
                {
                    url: '{{ route('tools.' . $module . '.download', ['file' => '__filename__']) }}',
                    className: 'btn btn-success p-1 download',
                    icon: '<i class="bx bx-download bx-xs" aria-hidden="true"></i>',
                    title: 'Unduh file backup',
                    modal: false
                }
            ],
            columns: [
                {
                    data: 'name',
                    name: 'name'
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
                initModalAjax('[data-bs-toggle="modal"]');
                initDatatableAction($(this), function() {
                    oTable.reload();
                });
            },
            onComplete: function() {
                initModalAjax('[data-bs-toggle="modal"]');
            },
            customRow: function(row, data) {
                let btn = $('td:eq(2)', row).find('.download');
                url = btn.attr('href').replace(/__filename__/g, data.name);

                btn.attr('href', url);
            }
        });

        $('.backup').click((e) => {
            let action = $(e.target).attr('data-action');
            let id = $(e.target).attr('id');
            $('.backup').attr('disabled', true).html('Backup process...')

            $.get(action + '?action=' + id, (response) => {
                $('.backup').removeAttr('disabled').html('Backup Database')
                oTable.reload();
            });
        });

        $('.cleansing').click((e) => {
            let action = $(e.target).attr('data-action');
            let id = $(e.target).attr('id');

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Akan membersihkan/menghapus data transaksi & backup database ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get(action + '?action=' + id, (response) => {
                        Swal.fire('Informasi', 'Database berhasil dibackup & dibersihkan/dihapus',
                            'info')
                        oTable.reload();
                    });
                }
            })

        });

        $(function() {
            initPage();
            initDatatableTools($('#main-table'), oTable);

            $('.download').click((e) => {
                e.preventDefault();
                let action = $(e.target).attr('data-action');
                $.get(action, (response) => {
                    oTable.reload();
                });
            });
        })
    </script>
@endpush
