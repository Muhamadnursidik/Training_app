<table class="table table-bordered">
    <thead>
        <tr>
            <th>NO</th>
            <th>PESAN</th>
            <th>TANGGAL</th>
        </tr>
    </thead>
    <tbody>
        @php
            $nomor = 1;
        @endphp
        @foreach ($mailData as $item)
            <tr>
                <td>{{ $nomor++ }}</td>
                <td>{{ $item->msg }}</td>
                <td>{{ Carbon\Carbon::parse($item->created_at)->isoFormat('DD MMMM YYYY HH:m:s') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>