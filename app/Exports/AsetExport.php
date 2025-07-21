<?php

namespace App\Exports;

use App\Models\AsetData;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AsetExport implements FromQuery, WithHeadings, WithTitle, WithColumnFormatting
{
    public function query()
    {
        // return AsetData::with(['parameter', 'subdata'])->select('kode_subdata', 'uraian', 'nama_barang', 'merek_barang', 'type_barang', 'ukuran_barang', 'bahan', 'harga_beli', 'tahun_beli', 'lokasi', 'kondisi_barang', 'keterangan')->orderBy('id');
        return DB::table('aset_data as ad')->leftJoin('master_subdatas as sub', 'sub.uuid_subdata', 'ad.kode_utama')->select('sub.kode_subdata', 'ad.kode_urut', 'sub.uraian', 'ad.nama_barang', 'ad.merek_barang', 'ad.type_barang', 'ad.ukuran_barang', 'ad.bahan', 'ad.harga_beli', 'ad.tahun_beli', 'ad.lokasi', DB::raw('IF(ad.kondisi_barang = "b", "Baik", IF(ad.kondisi_barang = "rr", "Rusak Ringan", IF(ad.kondisi_barang = "rb", "Rusak Berat", "")))'), 'ad.keterangan')->orderBy('sub.kode_subdata')->orderBy('ad.tahun_beli');
    }

    public function headings(): array
    {
        return [
            'Kode Barang',
            'Register',
            'Uraian',
            'Nama Barang',
            'Merek Barang',
            'Tipe Barang',
            'Ukuran/Dimensi',
            'Bahan',
            'Harga Pembelian',
            'Tahun Pembelian',
            'Ruangan/Lokasi',
            'Kondisi Barang',
            'Keterangan',
        ];
    }

    public function title(): string
    {
        return 'Aset_Data_' . date('YmdHis');
    }

    public function columnFormats(): array
       {
           return [
               'I' => '_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)',
           ];
       }
}
