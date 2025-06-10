<?php

namespace App\Imports;

use App\Models\MasterData;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Ramsey\Uuid\Uuid;

class MasterImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas, WithStartRow
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
            if (!empty($row['kode']) && !empty($row['nama'])) {
                $kode = str_replace(' ', '', $row['kode']);
                $cek  = MasterData::where('kode_aset', $kode)->count();
                if ($cek > 0) {
                    $this->duplicate++;
                    $this->loc[] = $row;
                } else {
                    $uuid = Uuid::uuid7()->toString();
                    $data = [
                        'uuid_aset'     => $uuid,
                        'kode_aset'     => $kode,
                        'uraian'        => $row['nama'],
                        'keterangan'    => $row['keterangan'],
                        'created_at'    => date('Y-m-d H:i:s'),
                    ];

                    $save = MasterData::insert($data);
                    if ($save) {
                        $this->success++;
                    } else {
                        $this->incomplete++;
                    }
                }
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
