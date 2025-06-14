<?php

namespace App\Http\Controllers;

use App\Models\AsetData;
use App\Models\MasterData;
use App\Models\MasterSubdata;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $data = [
            'ruangan'   => AsetData::groupBy('lokasi')->orderBy('lokasi')->select('lokasi')->get(),
        ];

        return view('main.report_aset', $data);
    }

    public function labeling()
    {
        $data = [
            'lists'     => AsetData::with(['subdata', 'parameter'])->get(),
            'params'    => MasterData::all(),
            'subdata'   => MasterSubdata::all(),
        ];

        return view('main.print_aset', $data);
    }

    public function export()
    {
        $query = AsetData::groupBy('kode_utama')->groupBy('tahun_beli')->where('lokasi', 'BIDANG TKI');
        $filter = $query->get();

        $data = [];
        foreach ($filter as $key => $value) {
            $isi = AsetData::with(['subdata'])->where('kode_utama', $value->kode_utama)->where('tahun_beli', $value->tahun_beli)->get();
            $merek = [];
            $harga = [];
            $baik  = 0; $ringan = 0; $berat = 0;
            foreach ($isi as $m) {
                $merek[] = $m->merek_barang;
                $harga[] = intval($m->harga_beli);
                if ($m->kondisi == 'b') {
                    $baik++;
                } elseif ($m->kondisi == 'rr') {
                    $ringan++;
                } elseif ($m->kondisi == 'rb') {
                    $berat++;
                }
            }
            $merek = array_unique($merek);

            $data[] = [
                'jenis'             => $value->uraian,
                'nama_barang'       => '<button class="btn btn-info btn-round onclick="_detail(`'.base64_encode($value).'`)">' . $value->nama_barang . '</button>',
                'merek_barang'      => (count($merek) > 1 ? 'Variatif' : $merek[0]),
                'ukuran'            => $value->ukuran_barang,
                'bahan'             => $value->bahan,
                'tahun'             => $value->tahun_beli,
                'kode'              => $isi[0]->subdata->kode_subdata ?? null,
                'jumlah_barang'     => count($isi),
                'harga'             => array_sum($harga),
                'kondisi'           => [
                                            'baik'      => $baik,
                                            'ringan'    => $ringan,
                                            'berat'     => $baik
                                        ],
                'keterangan'        => (count($merek) > 1 ? ('Merek: ' . implode(', ', $merek)) : ''),
                'ruangan'           => $value->lokasi,
            ];
        }

        return $data;
    }

    public function print_aset()
    {
        //
    }

    public function serverside()
    {
        Carbon::setLocale('id');
        $request = Request();
        $draw = $_REQUEST['draw'] ?? 0;
        $row = $_REQUEST['start'] ?? 0;
        $rowperpage = $_REQUEST['length']; // Rows display per page
        // $columnIndex = $_REQUEST['order'][0]['column']; // Column index
        // $columnName = $_REQUEST['columns'][$columnIndex]['data']; // Column name
        // $columnSortOrder = $_REQUEST['order'][0]['dir']; // asc or desc
        $searchValue = $_REQUEST['search']['value']; // Search value

        $total = AsetData::groupBy('kode_utama')->groupBy('tahun_beli')->count();
        $query = AsetData::groupBy('kode_utama')->groupBy('tahun_beli');
        if (isset($request->ruang) && !empty($request->ruang)) {
            $query->where('lokasi', $request->ruang);
        }
        $filter = $query->get();

        $data = [];
        foreach ($filter as $key => $value) {
            $isi = AsetData::with(['subdata'])->where('kode_utama', $value->kode_utama)->where('tahun_beli', $value->tahun_beli)->orderBy('updated_at', 'desc')->orderBy('created_at', 'desc')->get();
            $merek = [];
            $harga = [];
            $baik  = 0; $ringan = 0; $berat = 0;
            foreach ($isi as $m) {
                $merek[] = $m->merek_barang;
                $harga[] = intval($m->harga_beli);
                if ($m->kondisi == 'b') {
                    $baik++;
                } elseif ($m->kondisi == 'rr') {
                    $ringan++;
                } elseif ($m->kondisi == 'rb') {
                    $berat++;
                }
            }
            $merek = array_unique($merek);

            $data[] = [
                'jenis'             => $value->uraian,
                'nama'              => '<div class="col-12"><button class="btn btn-outline-success btn-block btn-sm col-12" onclick="_detail(`'.base64_encode($value).'`, `'. base64_encode($isi) .'`, `Rp'. number_format(array_sum($harga)) .'`)" title="Klik untuk melihat detail data"><i class="ti ti-info-square-rounded"></i>&nbsp;' . $value->nama_barang . '</button></div>',
                'merek'             => (count($merek) > 1 ? 'Variatif' : $merek[0]),
                'ukuran'            => $value->ukuran_barang,
                'bahan'             => $value->bahan,
                'tahun'             => $value->tahun_beli,
                'kode'              => $isi[0]->subdata->kode_subdata ?? null,
                'jumlah'            => count($isi),
                'harga'             => 'Rp' . number_format(array_sum($harga)),
                'kondisi_baik'      => $baik,
                'kondisi_ringan'    => $ringan,
                'kondisi_berat'     => $baik,
                'keterangan'        => (count($merek) > 1 ? ('Merek: ' . implode(', ', $merek)) : ''),
                'ruangan'           => $value->lokasi,
                'update'            => !empty($isi[0]->updated_at) ? (Carbon::parse($isi[0]->updated_at)->isoFormat('LL')) : (Carbon::parse($isi[0]->created_at)->isoFormat('LL')),
            ];
        }

        ## Response
        $response = [
            "draw" => $draw ? intval($draw) : 0,
            "recordsTotal" => $total,
            "recordsFiltered" => count($filter),
            "data" => array_slice($data, $row, $rowperpage),
        ];

        return json_encode($response);
    }
}
