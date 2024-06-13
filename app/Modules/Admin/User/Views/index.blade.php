@extends('layout.app')

@section('content')
@include('layout.partials.breadcrumb',compact('breadcrumb'))
<!-- Default Light Table -->
<div class="row">
    <div class="col">
        <div class="card card-small mb-2">
            <div class="card-header border-bottom py-2">
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
                                                <label for="name" class="col-sm-3 col-form-label">{{ __('Nama / Username') }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="name" id="name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="name" class="col-sm-3 col-form-label">{{ __('Unit Kerja') }}</label>
                                                <div class="col-sm-9">
                                                    {{ Form::select('unit_kerja_code', $options_unit_kerja, null, ['class' => 'form-control select2']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row mb-1">
                                                <label for="name" class="col-sm-3 col-form-label">{{ __('Role') }}</label>
                                                <div class="col-sm-9">
                                                    {{ Form::select('roles', $role_options, null, ['class' => 'form-control select2']) }}
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
                    'header' => ['Nama', 'Username','Unit Kerja','Reviewer','Admin', 'Peran', 'Status'],
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
                modal: '#modal-lg',
                className: 'btn btn-primary btn-add me-1',
            },
            {
                id: 'import',
                url: '{{ route($module . ".import") }}',
                modal: '#modal-md',
                className: 'btn btn-warning btn-import',
                icon: '<i class="bx bx-upload bx-xs" data-feather="upload"></i>',
                title: 'Import User',
                toggle: 'modal'
            }
        ],
        actions: [
            {
                id : 'edit',
                url: '{{ route($module . '.edit', ['user' => '__grid_doc__']) }}',
                modal: '#modal-lg',
                className: "btn btn-xs btn-outline-secondary p-1 pb-1 btn-edit"
            },
            {
                id : 'delete',
                url: '{{ route($module . '.destroy', ['id' => '__grid_doc__']) }}',
            },
            {
                url: '{{ route($module . '.resetpassword', ['_id' => '__grid_doc__']) }}',
                className: 'btn btn-xs btn-warning p-1 pb-0 resetpassword',
                icon: '<i class="bx bx-refresh bx-xs" data-feather="refresh-cw"></i>',
                title: 'Reset Password',
                modal: false
            },
            {
                url: '{{ route($module . '.release', ['_id' => '__grid_doc__']) }}',
                className: 'btn btn-xs btn-primary p-1 pb-0 btn-release',
                icon: '<i class="bx bx-lock-open-alt bx-xs"></i>',
                title: 'Release User',
                modal: false
            }
        ],
        columns: [
            {data: 'name', name:'name'},
            {data: "{{$username}}", name:'{{$username}}'},
            {data: 'unitkerja.name', name:'unitkerja.name'},
            {data: 'is_reviewer', name:'is_reviewer'},
            {data: 'is_admin', name:'is_admin'},
            {data: 'roles', name:'roles', orderable:false},
            {data: 'status', name:'status'},
            {data: 'action', className: 'text-center'}
        ],
        onDraw : function() {
            initModalAjax('.btn-edit, .btn-import');
            initDatatableAction($(this), function(){
              oTable.reload();
            });

            var _import = '{{ auth()->user()->can($module.'.import') }}';
            if(_import != '1'){
                $('.btn-import').remove()
            }

            $('.resetpassword').click(function(e){
                e.preventDefault();
                let url = $(this).attr('href');
                Swal.fire({
                    title: __('Password akan direset?'),
                    text: "password akan direset ke pengaturan awal menggunakan username",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.get(url, function(response){
                            Swal.fire(
                                'Reset',
                                __('Password berhasil direset menggunakan username'),
                                'success'
                            )
                        });
                    }
                })
            })

            $('.btn-release').click(function(e){
                e.preventDefault();
                let url = $(this).attr('href');
                Swal.fire({
                    title: __('Release?'),
                    text: "User akan di release dari login",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.get(url, function(response){
                            Swal.fire(
                                'Release',
                                __('User berhasil di release'),
                                'success'
                            )
                            oTable.reload();
                        });
                    }
                })
            })

        },
        onComplete: function() {
            initModalAjax('.btn-add, .btn-import');

            $('.resetpassword').click(function(e){
                e.preventDefault();
                let url = $(this).attr('href');
                Swal.fire({
                    title: __('Password akan direset?'),
                    text: "password akan direset ke pengaturan awal menggunakan username",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.get(url, function(response){
                            Swal.fire(
                                'Reset',
                                __('Password berhasil direset menggunakan username'),
                                'success'
                            )
                        });
                    }
                })
            })

            $('.btn-release').click(function(e){
                e.preventDefault();
                let url = $(this).attr('href');
                Swal.fire({
                    title: __('Release?'),
                    text: "User akan di release dari login",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.get(url, function(response){
                            Swal.fire(
                                'Release',
                                __('User berhasil di release'),
                                'success'
                            )
                            oTable.reload();
                        });
                    }
                })
            })
        },
        customRow: function(row, data) {
            if (data.email_verified_at != '') {
                if (data.status == '1'){
                    $('td:eq(6)', row).html( __('Aktif') ).addClass('text-success');
                } else {
                    $('td:eq(6)', row).html( __('Tidak Aktif') ).addClass('text-danger');
                }
            } else {
                $('td:eq(6)', row).html( __('Belum Aktif') ).addClass('text-danger');
            }

            if(data.is_admin == '1'){
                $('td:eq(4)', row).html( __('Ya') ).addClass('text-success');
            } else {
                $('td:eq(4)', row).html( __('Tidak') ).addClass('text-danger');
            }

            if(data.is_reviewer == '1'){
                $('td:eq(3)', row).html( __('Ya') ).addClass('text-success');
            } else {
                $('td:eq(3)', row).html( __('Tidak') ).addClass('text-danger');
            }

            if(data.blokir != true){
                $('td:eq(7)', row).find('.btn-release').remove();
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
