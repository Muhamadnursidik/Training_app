@if ($type == 'export-pdf')
    @include('components.css')
    <style>
        @page {
            /* size: auto; */
            margin-top: 1.9cm;
            margin-header: 0.5cm;
            margin-footer: 0.5cm;
            header: page-header-one;
            footer: page-footer-one;
        }
    </style>
    <htmlpageheader name="page-header-one">
        <table class="table">
            <tr>
                <td class="noborder align-middle" width="15%" height="5px" colspan="8"
                    style="background-color:#ffffff">
                    @if (file_exists(public_path() . '/img/logo.png'))
                        <img src="{{ public_path('/img/logo.png') }}" width="17%" />
                    @else
                        <img src="{{ public_path('/img/logo-bartech.png') }}" />
                    @endif
                </td>
                <td class="noborder align-middle text-right" style="background-color:#ffffff">
                    <br>
                </td>

            </tr>
        </table>

    </htmlpageheader>
@endif
<hr>
<h3 class="text-center font-weight-bold">
    LAPORAN LOG ACTIVITY <br> {{ strtoupper(config('data.app_name')) }}<br>
    PERIODE {{ strtoupper(Carbon\Carbon::createFromFormat('d-m-Y', $filter['tanggal'])->isoFormat('DD MMMM YYYY')) }}
</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="padding:10px">No</th>
            <th style="padding:10px">User</th>
            <th style="padding:10px">Username</th>
            <th style="padding:10px">Tabel Terkait</th>
            <th>Aktivitas</th>
            <th>Tanggal Aktivitas</th>
            <th>IP Address</th>
            <th>OS</th>
            <th>Browser</th>
        </tr>
    </thead>

    <tbody>

        @if (count($data) > 0)
            @foreach ($data['data'] as $value)
                @php
                    $tabelTerkait = '';

                    if ($value['subject_type'] != null) {
                        $tabelTerkait = app($value['subject_type'])->getTable();
                    }

                @endphp
                <tr>
                    <td style="padding:5px;" class="text-center">{{ $loop->iteration }}</td>
                    <td style="padding:5px;">
                        {{ $value['user'] ? $value['user']['name'] : $value['properties.username'] }}</td>
                    <td style="padding:5px;">
                        {{ $value['user'] ? $value['user']['username'] : $value['properties.username'] }}</td>
                    <td style="padding:5px;">{{ $tabelTerkait }}</td>
                    <td style="padding:5px;">{{ $value['description'] }}</td>
                    <td style="padding:5px;">
                        {{ $value['created_at'] }}</td>
                    <td>{{ $value['properties.ip_address'] }}</td>
                    <td>{{ $value['properties.os'] }}</td>
                    <td>{{ $value['properties.browser'] }}</td>
                </tr>
                <br>
            @endforeach
        @else
            <tr>
                <td class="align-middle text-center" colspan="5">Data tidak ditemukan</td>
            </tr>
        @endif
    </tbody>
</table>
