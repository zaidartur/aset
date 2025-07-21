<table style="border: 2px solid black; width: auto;">
    <thead>
        <tr style="border: 2px solid black;">
            <th colspan="3" style="text-align: center; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Kode Lengkap</th>

            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Uraian</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Nama Barang</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Merek Barang</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Tipe Barang</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Ukuran/Dimensi</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Bahan</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Harga Pembelian</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Ruangan/Lokasi</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Kondisi Barang</th>
            <th rowspan="2" style="text-align: center; vertical-align: middle; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Keterangan</th>
        </tr>
        <tr style="border: 2px solid black;">
            <th style="text-align: center; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Kode Barang</th>
            <th style="text-align: center; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Register</th>
            <th style="text-align: center; font-weight: bold; border: 2px solid black; background-color: #ffcc02;">Tahun Pembelian</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            <tr style="border: 2px solid black;">
                <td style="border: 2px solid black;">{{ $data->kode }}</td>
                <td style="border: 2px solid black;">{{ $data->urutan }}</td>
                <td style="border: 2px solid black;">{{ $data->tahun_beli }}</td>
                <td style="border: 2px solid black;">{{ $data->uraian }}</td>
                <td style="border: 2px solid black;">{{ $data->nama_barang }}</td>
                <td style="border: 2px solid black;">{{ $data->merek_barang }}</td>
                <td style="border: 2px solid black;">{{ $data->type_barang }}</td>
                <td style="border: 2px solid black;">{{ $data->ukuran_barang }}</td>
                <td style="border: 2px solid black;">{{ $data->bahan }}</td>
                <td style="border: 2px solid black;">Rp {{ number_format($data->harga_beli) }}</td>
                <td style="border: 2px solid black;">{{ $data->lokasi }}</td>
                <td style="border: 2px solid black;">{{ ($data->kondisi_barang == 'b' ? 'Baik' : ($data->kondisi_barang == 'rr' ? 'Rusak Ringan' : ($data->kondisi_barang == 'rb' ? 'Rusak Berat' : ''))) }}</td>
                <td style="border: 2px solid black;">{{ $data->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>