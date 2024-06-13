@extends('layout.modal')

@if ($type != 'export-pdf')
    @section('title', __('Detail Log Aktivity'))
@endif


@section('content')
    @if ($type == 'export-pdf')
        @include('components.css')
        <style>
            body {
                font-size: 9pt;
            }

            @page {
                /* size: auto; */
                margin-top: 1cm;
                margin-header: 0.5cm;
                margin-footer: 0.5cm;
                header: page-header-one;
                footer: page-footer-one;
            }

            #footer {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: white;
                border-top: 1px solid black;
                padding: 10px;
            }
        </style>
        <htmlpageheader name="page-header-one">
            <table class="table">
                <tr>
                    <td class="noborder align-middle" width="15%" height="5px" colspan="8"
                        style="background-color:#ffffff">
                        @if (file_exists(public_path() . '/img/logo.png'))
                        <img src="{{ public_path('/img/logo.png') }}" width="17%"/>
                        @else
                            <img src="{{ public_path('/img/logo-bartech.png') }}" />
                        @endif
                    </td>
                    <td class="noborder align-middle text-right" style="background-color:#ffffff">
                        <br>
                    </td>

                </tr>
            </table>
            <hr>
        </htmlpageheader>
    @endif

    @if ($type == 'export-pdf')
        <br>
        <br>
        <h3 class="text-center font-weight-bold">
            LAPORAN DETAIL {{ strtoupper($data->user->name) }} LOG ACTIVITY <br>{{ strtoupper(config('data.app_name')) }}
        </h3>
    @endif


    
        <div class="modal-body table-responsive">
            @php
                $detail = json_decode($data->properties);
            @endphp
            @if (!in_array($data->log_name, ['login', 'logout', 'lock', 'unlock']))
                @if ($detail)
                    <table class="table table-bordered mb-3">
                        <tr>
                            <td>User</td>
                            <td>{{ $data->user->name }}</td>
                        </tr>
                        @if (isset($data->subject_type) && $data->subject_type != '')
                            <tr>
                                <td>Modul</td>
                                <td>{{ $data->subject_type }}</td>
                            </tr>
                        @endif
                        @if (isset($tabelTerkait) && $tabelTerkait != '')
                            <tr>
                                <td>Tabel Terkait</td>
                                <td>{{ $tabelTerkait }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>Aksi</td>
                            <td>{{ $data->description }}</td>
                        </tr>
                        <tr>
                            <td>Tgl Aktivitas</td>
                            <td>{{ date('Y-m-d H:i:s', strtotime($data->created_at)) }}
                            </td>
                        </tr>
                        <tr>
                            <td>IP Address</td>
                            <td>{{ isset($detail->ip_address) ? $detail->ip_address : 'n/a' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Device</td>
                            <td>{{ isset($detail->device) ? $detail->device : 'n/a' }}
                            </td>
                        </tr>
                        <tr>
                            <td>OS</td>
                            <td>{{ isset($detail->os) ? $detail->os : 'n/a' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Browser</td>
                            <td>{{ isset($detail->browser) ? $detail->browser : 'n/a' }}
                            </td>
                        </tr>
                    </table>
                    <table class="table table-bordered mb-2" @if($type) style="margin-top: 20px" @endif>
                        @if (isset($detail->attributes))
                            @foreach ($detail as $key => $item)
                                @php
                                    if ($key != 'old' && $key != 'attributes') {
                                        break;
                                    }
                                @endphp
                                    <tr>
                                        <th colspan="2" class="text-center">{{ $key == 'old' ? 'Sebelum' : 'Sesudah' }}
                                        </th>
                                    </tr>
                                @if (is_array($item) || is_object($item))
                                    @foreach ($item as $k => $d)
                                        <tr>
                                            <td width="30%">{{ $k }}</td>
                                            @if (is_array($d) || is_object($d))
                                                <td>{{ json_encode($d) }}</td>
                                            @else
                                                <td>{{ $d }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td width="30%">{{ $key }}</td>
                                        <td>{{ $item }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            @if (!in_array($data->log_name, ['Akses menu']))
                                <tr>
                                    <th colspan="2" class="text-center">{{ 'Sesudah' }}
                                    </th>
                                </tr>
                                @foreach ($detail as $key => $item)
                                    @php
                                        if ($key != 'old' && $key != 'attributes') {
                                            break;
                                        }
                                    @endphp
                                    @if (is_array($item) || is_object($item))
                                        @foreach ($item as $k => $d)
                                            <tr>
                                                <td width="30%">{{ $k }}</td>
                                                @if (is_array($d) || is_object($d))
                                                    <td>{{ json_encode($d) }}</td>
                                                @else
                                                    <td>{{ $d }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td width="30%">{{ $key }}</td>
                                            <td>{{ $item }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </table>
                @endif
            @elseif(in_array($data->log_name, ['login', 'logout']))
                <table class="table table-bordered mb-2">
                    <tr>
                        <td>Nama Log</td>
                        <td>{{ $data->log_name }}</td>
                    </tr>
                    <tr>
                        <td>Deskripsi</td>
                        <td>{{ $data->description }}</td>
                    </tr>
                    <tr>
                        <td>Pada Ip Address</td>
                        <td> {{ $detail->ip_address }} </td>
                    </tr>
                </table>
            @else
                <table class="table table-bordered mb-2">
                    <tr>
                        <td>Nama Log</td>
                        <td>{{ $data->log_name }}</td>
                    </tr>
                    <tr>
                        <td>Deskripsi</td>
                        <td>{{ $data->description }}</td>
                    </tr>
                    <tr>
                        <td>Posisi</td>
                        <td>{{ $detail->posisi }}</td>
                    </tr>
                    <tr>
                        <td>Pada Ip Address</td>
                        <td> {{ $detail->ip_address }} </td>
                    </tr>
                </table>
            @endif
        </div>
        @if ($type != 'export-pdf')
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary export-pdf" id="export-pdf" 
                        data-id="{{ encrypt($data['id']) }}" type="button"> Export PDF</button>
                <button type="button" class="btn btn-sm btn-secondary"
                    data-bs-dismiss="modal">{{ __('Tutup') }}</button>
            </div>
        @endif
    
@endsection

@push('plugin-scripts')
    <script>
        $('.export-pdf').on('click', function(e) {
            let elexport = $(this).attr('id');
            let id = $(this).attr('data-id');

            let url = "activitylog/detail" + "?type=" + elexport + "&id=" +
                id;
            window.open(url, '_blank');
        });
    </script>
@endpush
