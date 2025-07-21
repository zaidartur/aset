<?php

namespace App\Exports;

use App\Models\AsetData;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AsetExport implements FromView
{
    public function view(): View
    {
        $data = DB::table('aset_data as ad')->leftJoin('master_subdatas as sub', 'sub.uuid_subdata', 'ad.kode_utama')
                ->select(
                    'sub.kode_subdata', 
                    'ad.kode_urut', 
                    'sub.uraian', 
                    'ad.nama_barang', 
                    'ad.merek_barang', 
                    'ad.type_barang', 
                    'ad.ukuran_barang', 
                    'ad.bahan', 
                    'ad.harga_beli', 
                    'ad.tahun_beli', 
                    'ad.lokasi', 
                    'ad.kondisi_barang', 
                    'ad.keterangan'
                )
                ->orderBy('sub.kode_subdata')->orderBy('ad.tahun_beli')->get();
        foreach ($data as $key => $value) {
            $xkode = explode('.', $value->kode_subdata);
            $implode = '';
            foreach ($xkode as $c => $code) {
                if ($c < 3) {
                    $implode .= $code . '.';
                } elseif ($c == 3) {
                    if (intval($code) < 10) {
                        $implode .= '0' . $code . '.';
                    } else {
                        $implode .= $code . '.';
                    }
                } else {
                    if (intval($code) < 10) {
                        $implode .= '00' . $code;
                    } elseif (intval($code) > 9 && intval($code) < 100) {
                        $implode .= '0' . $code;
                    } else {
                        $implode .= $code;
                    }
                }
            }

            $urutan = '';
            if (intval($value->kode_urut) < 9) {
                $urutan .= '00' . $value->kode_urut;
            } elseif (intval($value->kode_urut) > 9 && intval($value->kode_urut) < 99) {
                $urutan .= '0' . $value->kode_urut;
            } else {
                $urutan .= $value->kode_urut;
            }

            $value->kode    = $implode;
            $value->urutan  = $urutan;
        }
        
        return view('main.export_aset', ['datas' => $data]);
    }

    // public function query()
    // {
    //     // return AsetData::with(['parameter', 'subdata'])->select('kode_subdata', 'uraian', 'nama_barang', 'merek_barang', 'type_barang', 'ukuran_barang', 'bahan', 'harga_beli', 'tahun_beli', 'lokasi', 'kondisi_barang', 'keterangan')->orderBy('id');
    //     return DB::table('aset_data as ad')->leftJoin('master_subdatas as sub', 'sub.uuid_subdata', 'ad.kode_utama')
    //             ->select(
    //                 DB::raw('sub.kode_subdata'), 
    //                 DB::raw('IF(ad.kode_urut < 10, CONCAT("00", ad.kode_urut), IF(ad.kode_urut > 9 AND ad.kode_urut < 100, CONCAT("0", ad.kode_urut), ad.kode_urut))'), 
    //                 'sub.uraian', 
    //                 'ad.nama_barang', 
    //                 'ad.merek_barang', 
    //                 'ad.type_barang', 
    //                 'ad.ukuran_barang', 
    //                 'ad.bahan', 
    //                 'ad.harga_beli', 
    //                 'ad.tahun_beli', 
    //                 'ad.lokasi', 
    //                 DB::raw('IF(ad.kondisi_barang = "b", "Baik", IF(ad.kondisi_barang = "rr", "Rusak Ringan", IF(ad.kondisi_barang = "rb", "Rusak Berat", "")))'), 
    //                 'ad.keterangan'
    //             )
    //             ->orderBy('sub.kode_subdata')->orderBy('ad.tahun_beli');
    // }

    // public function headings(): array
    // {
    //     return [
    //         'Kode Barang',
    //         'Register',
    //         'Uraian',
    //         'Nama Barang',
    //         'Merek Barang',
    //         'Tipe Barang',
    //         'Ukuran/Dimensi',
    //         'Bahan',
    //         'Harga Pembelian',
    //         'Tahun Pembelian',
    //         'Ruangan/Lokasi',
    //         'Kondisi Barang',
    //         'Keterangan',
    //     ];
    // }

    // public function title(): string
    // {
    //     return 'Aset_Data_' . date('YmdHis');
    // }

    // public function columnFormats(): array
    //    {
    //        return [
    //            'I' => '_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)',
    //        ];
    //    }
}
