<table>
    <thead>
        <tr>
            <th>Kode Barang</th>
            <th>Register</th>
            <th>Uraian</th>
            <th>Nama Barang</th>
            <th>Merek Barang</th>
            <th>Tipe Barang</th>
            <th>Ukuran/Dimensi</th>
            <th>Bahan</th>
            <th>Harga Pembelian</th>
            <th>Tahun Pembelian</th>
            <th>Ruangan/Lokasi</th>
            <th>Kondisi Barang</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            <tr>
                <td>{{ $data->kode }}</td>
                <td>{{ $data->urutan }}</td>
                <td>{{ $data->uraian }}</td>
                <td>{{ $data->nama_barang }}</td>
                <td>{{ $data->merek_barang }}</td>
                <td>{{ $data->type_barang }}</td>
                <td>{{ $data->ukuran_barang }}</td>
                <td>{{ $data->bahan }}</td>
                <td>Rp {{ number_format($data->harga_beli) }}</td>
                <td>{{ $data->tahun_beli }}</td>
                <td>{{ $data->lokasi }}</td>
                <td>{{ ($data->kondisi_barang == 'b' ? 'Baik' : ($data->kondisi_barang == 'rr' ? 'Rusak Ringan' : ($data->kondisi_barang == 'rb' ? 'Rusak Berat' : ''))) }}</td>
                <td>{{ $data->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>