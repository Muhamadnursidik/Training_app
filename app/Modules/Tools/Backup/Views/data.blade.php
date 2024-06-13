<link rel="stylesheet" media="print" href="{{ asset('css/print.min.css') }}" />

<h5 class="text-center font-weight-bold">
    <p>LAPORAN DAFTAR ADJUSTMENT PERINGKAT RISIKO</p>
    <p>{{ !empty($unitkerja) ? strtoupper($unitkerja['name']) : '' }}</p>
    <p>PERIODE LAPORAN {{ $periode }}</p> 
</h5>

<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2" class="align-middle text-center">No</th>        
            <th rowspan="2" class="align-middle text-center">Tanggal</th>        
            <th rowspan="2" class="align-middle text-center">Peristiwa Risiko</th>               
            <th rowspan="2" class="align-middle text-center">Jenis Kejadian</th>        
            <th rowspan="2" class="align-middle text-center">Penyebab</th>                
            <th colspan="2" class="align-middle text-center">Dampak</th>        
            <th rowspan="2" class="align-middle text-center">Peringkat Awal</th>        
            <th rowspan="2" class="align-middle text-center">Peringkat Adjustment</th>               
            <th rowspan="2" class="align-middle text-center">Keterangan</th>               
        </tr>
        <tr>
            <th class="align-middle text-center">Finansial</th>
            <th class="align-middle text-center">Non Finansial</th>
        </tr>
    </thead>
    {{-- <body> --}}
        @if ($result['data']->count() > 0)
            @php $i = 1; @endphp
            @foreach ($result['data'] as $item)
                <tr>
                    <td class="align-baseline">{{ $i++ }}</td>
                    <td class="align-baseline">
                        <p class="font-weight-bold">Tgl. Kejadian:</p>
                        {{ Carbon\Carbon::parse($item['tgl_kejadian'])->format('d-m-Y') }}
                        <p class="font-weight-bold">Tgl. Diketahui: </p>
                        {{ Carbon\Carbon::parse($item['tgl_diketahui'])->format('d-m-Y') }}
                        <p class="font-weight-bold">Tgl. Lapor: </p>
                        {{ Carbon\Carbon::parse($item['created_at'])->format('d-m-Y') }}
                    </td>
                    <td class="align-baseline">
                        <p class="font-weight-bold">Peristiwa Risiko:</p>
                        {{ $item['katalogrisiko']['name'] }}
                        <p class="font-weight-bold">Deskripsi Peristiwa Risiko:</p>
                        {{ $item['penjelasan_isu'] }}
                    </td>
                    <td class="align-baseline">
                        {{ App\Helpers\Common::changeLabelJeniskejadian($item['jenis_kerugian']) }}
                    </td>
                    <td class="align-baseline">
                        <p><strong>Penyebab:</strong> 
                            {{ $item['penyebabutama']['name'] }}
                        </p>
                        <p>
                            <strong>Penyebab Detail:</strong> 
                            <ol class="pl-3">
                            @foreach ($item['penyebabutamadetail'] as $penyebab)
                                <li>{{ $penyebab['name'] }}</li>
                            @endforeach
                            </ol>
                        </p>
                    </td>
                    <td class="align-baseline">
                        <p class="font-weight-bold">Kerugian Aktual:</p>
                        {{ App\Helpers\Common::formatNumber(($item['jenis_kerugian'] == 'lossevent') ? $item['dampak_finansial'] : 0) }}
                        <p class="font-weight-bold">Potensi Kerugian:</p>
                        {{ App\Helpers\Common::formatNumber(($item['jenis_kerugian'] != 'lossevent') ? $item['dampak_finansial'] : 0) }}
                    </td>
                    <td class="align-baseline">
                        @foreach ($item['dampaknonfinansial'] as $dampak)
                            <p class="font-weight-bold">{{ $item['dampaknonfinancial']['name'] }}</p>
                            {{ $dampak['peringkat'] }}. {{ $dampak['name'] }}
                        @endforeach
                    </td>
                    <td class="align-baseline text-center">
                        {{ $predikat[$item['peringkat']]['label'] }}
                    </td>
                    <td class="align-baseline">
                        {{ $predikat[$item['peringkat_adjustment']]['label'] }}    
                    </td>
                    <td class="align-baseline">
                        @php
                            $keterangan = $item->historireview->last()->keterangan_adjustment;
                        @endphp
                        {{ $keterangan }}    
                    </td>
                </tr>    
            @endforeach
        @else
                <tr>
                    <td colspan="10" class="text-center">Laporan masih kosong</td>
                </tr>
        @endif

    {{-- </body> --}}
</table>