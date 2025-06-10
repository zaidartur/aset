<?php

namespace App\Imports;

use App\Models\MasterData;
use App\Models\MasterSubdata;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Ramsey\Uuid\Uuid;

class SubParameter implements ToCollection, WithHeadingRow, WithCalculatedFormulas, WithStartRow
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
            if (!empty($row['kode']) && !empty($row['nama']) && !empty($row['parent'])) {
                $kode   = str_replace(' ', '', $row['kode']);
                $parent = MasterData::where('kode_aset', str_replace(' ', '', $row['parent']))->first();
                $cek    = MasterSubdata::where('kode_subdata', $kode)->count();
                if ($cek > 0) {
                    $this->duplicate++;
                    $this->loc[] = $row;
                } else {
                    if ($parent) {
                        $uuid = Uuid::uuid7()->toString();
                        $data = [
                            'uuid_subdata'      => $uuid,
                            'kode_subdata'      => $kode,
                            'parent'            => $parent->uuid_aset,
                            'uraian'            => $row['nama'],
                            'keterangan'        => $row['keterangan'],
                            'created_at'        => date('Y-m-d H:i:s'),
                        ];

                        $save = MasterSubdata::insert($data);
                        if ($save) {
                            $this->success++;
                        } else {
                            $this->incomplete++;
                        }
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
