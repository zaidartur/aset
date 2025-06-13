<?php

namespace App\Imports;

use App\Models\AsetData;
use App\Models\MasterSubdata;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Ramsey\Uuid\Uuid;

class AsetImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas, WithStartRow
{
    /**
    * @param Collection $collection
    */

    public $success = 0;
    public $total = 0;
    public $incomplete = 0;
    public $duplicate = 0;
    public $loc = [];

    public function collection(Collection $rows)
    {
        $this->total = count($rows);
        foreach ($rows as $key => $row) {
            if (!empty($row['nama']) && !empty($row['merek']) && !empty($row['lokasi']) && !empty($row['kode']) && !empty($row['register']) && !empty($row['tahun']) && !empty($row['harga']) && !empty($row['kondisi'])) {
                $uuid = Uuid::uuid4()->toString();
                $cek  = MasterSubdata::where('kode_subdata', $row['kode'])->get();
                if (count($cek) > 0) {
                    $param = $cek[0];
                    $data = [
                        'uuid_barang'   => $uuid,
                        'kode_parent'   => $param->parent,
                        'kode_utama'    => $param->uuid_subdata,
                        'kode_urut'     => intval($row['register']),
                        'uraian'        => $param->uraian,
                        'nama_barang'   => $row['nama'],
                        'merek_barang'  => $row['merek'],
                        'type_barang'   => $row['tipe'] ?? null,
                        'ukuran_barang' => null,
                        'bahan'         => $row['bahan'] ?? '-',
                        'harga_beli'    => intval($row['harga']),
                        'tahun_beli'    => $row['tahun'],
                        'lokasi'        => $row['lokasi'],
                        'kondisi_barang'=> strtolower($row['kondisi']),
                        'keterangan'    => $row['keterangan'] ?? null,
                        'user_id'       => Auth::user()->uuid,
                        'created_at'    => date('Y-m-d H:i:s'),
                    ];

                    $save = AsetData::insert($data);
                    if ($save) {
                        $this->success++;
                    } else {
                        $this->incomplete++;
                    }
                } else {
                    $this->incomplete++;
                    $this->loc[] = $row;
                }
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
