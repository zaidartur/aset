<?php

namespace App\Http\Controllers;

use App\Models\AsetData;
use App\Models\MasterData;
use App\Models\MasterSubdata;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            'lists'     => AsetData::count(),
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
        $request = Request();
        if ($request->uuid == 'all') {
            $array = AsetData::with(['subdata'])->get();
        } else {
            $exp = explode(',', base64_decode($request->uuid));
            $array = AsetData::with(['subdata'])->whereIn('uuid_barang', $exp)->get();
        }

        // converting image
        $path = public_path('assets/img/logo-kra.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        foreach ($array as $key => $value) {
            if (!empty($value->updated_at)) {
                $tgl = $value->updated_at;
            } else {
                $tgl = $value->created_at;
            }
            $xkode = explode('.', $value->subdata->kode_subdata);
            $implode = '';
            foreach ($xkode as $c => $code) {
                if ($c < 3) {
                    $implode .= $code . '.';
                } elseif ($c == 3) {
                    if (intval($code) < 9) {
                        $implode .= '0' . $code . '.';
                    } else {
                        $implode .= $code . '.';
                    }
                } else {
                    if (intval($code) < 9) {
                        $implode .= '00' . $code . '.';
                    } elseif (intval($code) > 9 && intval($code) < 99) {
                        $implode .= '0' . $code . '.';
                    } else {
                        $implode .= $code . '.';
                    }
                }
            }

            if (intval($value->kode_urut) < 9) {
                $implode .= '00' . $value->kode_urut;
            } elseif (intval($value->kode_urut) > 9 && intval($value->kode_urut) < 99) {
                $implode .= '0' . $value->kode_urut;
            } else {
                $implode .= $value->kode_urut;
            }

            $text = 'UUID : ' . $value->uuid_barang .
                    "\n" . 'Kode Barang : ' . $implode .
                    "\n" . 'Nama Barang : ' . $value->nama_barang .
                    "\n" . 'Merek Barang : ' . $value->merek_barang .
                    "\n" . 'Ruangan : ' . $value->lokasi .
                    "\n" . 'Update Terakhir : ' . Carbon::parse($tgl)->isoFormat('LL');

            $value->kode = $implode;
            $value->qrcode = base64_encode(QrCode::size(120)
                            ->format('svg')
                            ->style('square')
                            ->errorCorrection('L')
                            ->generate($text));
        }        

        $data = [
            'lists' => $array ?? [],
            'logo'  => $base64,
            // 'qrcode'=> $qrcode,
            'size'  => $request->size,
        ];

        // return view('main.print_template', $data);
        $pdf = Pdf::loadView('main.print_template', $data)->setPaper('A4', 'portrait')->setOption([
            'fontDir'       => public_path('/assets/fonts/PlusJakartaSans/Fonts/OTF/'),
            'fontCache'     => public_path('/assets/fonts/PlusJakartaSans/Fonts/OTF/'),
            'defaultFont'   => 'Plus Jakarta Sans',
        ]);

        return $pdf->stream();
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

    public function serverside_label()
    {
        $request = Request();
        $draw = $_REQUEST['draw'] ?? 0;
        $row = $_REQUEST['start'] ?? 0;
        $rowperpage = $_REQUEST['length']; // Rows display per page
        $columnIndex = $_REQUEST['order'][0]['column']; // Column index
        $columnName = $_REQUEST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_REQUEST['order'][0]['dir']; // asc or desc
        $searchValue = $_REQUEST['search']['value']; // Search value

        $total = AsetData::count();
        $query = AsetData::with(['subdata', 'parameter']);
        if (!empty($searchValue)) {
            $query->where('nama_barang', 'like', '%' . $searchValue . '%')
                    ->orWhere('merek_barang', 'like', '%' . $searchValue . '%')
                    ->orWhere('tipe_barang', 'like', '%' . $searchValue . '%')
                    ->orWhere('uraian', 'like', '%' . $searchValue . '%')
                    ->orWhere('tahun_barang', 'like', '%' . $searchValue . '%')
                    ->orWhere('harga_beli', 'like', '%' . $searchValue . '%')
                    ->orWhere('lokasi', 'like', '%' . $searchValue . '%')
                    ->orWhere('keterangan', 'like', '%' . $searchValue . '%');
        }
        $filter = $query->get();

        $data = [];
        foreach ($filter as $key => $value) {
            if ($value->kondisi_barang == 'b') {
                $kondisi = '<span class="badge rounded-pill bg-success bg-glow"><svg  xmlns="http://www.w3.org/2000/svg"  width="14"  height="14"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-rosette-discount-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12.01 2.011a3.2 3.2 0 0 1 2.113 .797l.154 .145l.698 .698a1.2 1.2 0 0 0 .71 .341l.135 .008h1a3.2 3.2 0 0 1 3.195 3.018l.005 .182v1c0 .27 .092 .533 .258 .743l.09 .1l.697 .698a3.2 3.2 0 0 1 .147 4.382l-.145 .154l-.698 .698a1.2 1.2 0 0 0 -.341 .71l-.008 .135v1a3.2 3.2 0 0 1 -3.018 3.195l-.182 .005h-1a1.2 1.2 0 0 0 -.743 .258l-.1 .09l-.698 .697a3.2 3.2 0 0 1 -4.382 .147l-.154 -.145l-.698 -.698a1.2 1.2 0 0 0 -.71 -.341l-.135 -.008h-1a3.2 3.2 0 0 1 -3.195 -3.018l-.005 -.182v-1a1.2 1.2 0 0 0 -.258 -.743l-.09 -.1l-.697 -.698a3.2 3.2 0 0 1 -.147 -4.382l.145 -.154l.698 -.698a1.2 1.2 0 0 0 .341 -.71l.008 -.135v-1l.005 -.182a3.2 3.2 0 0 1 3.013 -3.013l.182 -.005h1a1.2 1.2 0 0 0 .743 -.258l.1 -.09l.698 -.697a3.2 3.2 0 0 1 2.269 -.944zm3.697 7.282a1 1 0 0 0 -1.414 0l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.32 1.497l2 2l.094 .083a1 1 0 0 0 1.32 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>&nbsp;Baik</span>';
            } elseif ($value->kondisi_barang == 'rr') {
                $kondisi = '<span class="badge rounded-pill bg-warning bg-glow"><svg  xmlns="http://www.w3.org/2000/svg"  width="16"  height="16"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-egg-cracked"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.236 2.066l-1.694 5.647l-.029 .123a1 1 0 0 0 .406 .978l2.764 1.974l-1.551 2.716a1 1 0 1 0 1.736 .992l2 -3.5l.052 -.105a1 1 0 0 0 -.339 -1.205l-2.918 -2.085l1.623 -5.41c3.641 1.074 6.714 6.497 6.714 11.892c0 4.59 -3.273 7.71 -8 7.917c-4.75 0 -8 -3.21 -8 -7.917c0 -5.654 3.372 -11.344 7.236 -12.017" /></svg>&nbsp;Rusak Ringan</span>';
            } else {
                $kondisi = '<span class="badge rounded-pill bg-danger bg-glow"><svg  xmlns="http://www.w3.org/2000/svg"  width="14"  height="14"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-alert-triangle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 1.67c.955 0 1.845 .467 2.39 1.247l.105 .16l8.114 13.548a2.914 2.914 0 0 1 -2.307 4.363l-.195 .008h-16.225a2.914 2.914 0 0 1 -2.582 -4.2l.099 -.185l8.11 -13.538a2.914 2.914 0 0 1 2.491 -1.403zm.01 13.33l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -7a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z" /></svg>&nbsp;Rusak Berat</span>';
            }
            $data[] = [
                'kode'          => $value->subdata->kode_subdata,
                'nama'          => $value->nama_barang,
                'merek'         => $value->merek_barang,
                'tipe'          => $value->type_barang,
                'tahun'         => $value->tahun_beli,
                'ruang'         => $value->lokasi,
                'kondisi'       => $kondisi,
                'keterangan'    => $value->keterangan,
                'checkbox'      => '<div class="checkbox"><input class="form-check-input select-print" type="checkbox" id="check_' . $value->uuid_barang . '" onclick="checkcheckbox();" value="' . $value->uuid_barang . '"/><label for="check_' . $value->uuid_barang . '">&nbsp;</label></div>',
                'opsi'          => '<button type="button" class="btn rounded-pill btn-icon btn-info waves-effect waves-light" onclick="_detail(`'. $value->uuid_barang .'`)" title="Detail Barang"><i class="ti ti-info-circle"></i></button>' .
                                    '&nbsp;&nbsp;' .
                                    '<button type="button" id="printItem" class="btn rounded-pill btn-warning waves-effect waves-light dropdown-toggle" title="Print Barang" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-printer"></i></button>
                                    <div class="dropdown-menu" aria-labelledby="printItem" style="">
                                        <a class="dropdown-item waves-effect" href="javascript:void(0);" onclick="_print(`'. base64_encode($value) .'`, `big`)">Ukuran Besar</a>
                                        <a class="dropdown-item waves-effect" href="javascript:void(0);" onclick="_print(`'. base64_encode($value) .'`, `small`)">Ukuran Kecil</a>
                                    </div>'
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
