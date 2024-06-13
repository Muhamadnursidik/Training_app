<link rel="stylesheet" media="print" href="{{ asset('css/print.min.css') }}" />
@if ($type)
    @include('components.css')
@endif
<h5 class="text-center font-weight-bold">
    <p>DATA MAPPING ROLE & BAGIAN</p>
    <p>ROLE {{ strtoupper($role->name) }}</p>
</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <td>No</td>
            <td>Jabatan</td>
            <td>Divisi/Bagian/Bidang</td>
            <td>Kantor</td>
        </tr>
    </thead>
    <tbody>
        @if (count($data) > 0)
            @php $i = 1;
            @endphp
            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $key }}</td>
                    <td>
                        @foreach ($value['bagian'] as $bagian)
                            <p>{{$bagian}}</p>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($value['unit_kerja'] as $unit_kerja)
                            <p>{{$unit_kerja}}</p>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-center">Data tidak ditemukan</td>
            </tr>
        @endif
    </tbody>
</table>

