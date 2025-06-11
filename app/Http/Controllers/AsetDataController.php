<?php

namespace App\Http\Controllers;

use App\Models\AsetData;
use App\Models\MasterSubdata;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class AsetDataController extends Controller
{
    public function index()
    {
        $data = [
            'lists'     => AsetData::with(['subdata', 'parameter'])->get(),
            'subdata'   => MasterSubdata::all(),
        ];

        return view('main.data', $data);
    }

    public function save(Request $request)
    {
        $request->validate([
            'subadata'  => 'required|string',
            'nama'      => 'required|string',
            'merek'     => 'required|string',
            'harga'     => 'required|numeric',
            'tahun'     => 'required|numeric',
            'ruang'     => 'required|string',
            'kondisi'   => 'required|string',
        ]);

        $uuid = Uuid::uuid4()->toString();
        $cek  = MasterSubdata::where('uuid_subdata', $request->subdata)->first();
        
        if ($cek) {
            $no   = AsetData::where('kode_utama', $request->subdata)->where('tahun_beli', $request->tahun)->orderBy('kode_urut', 'desc')->first();
            if ($no) {
                $nomor = (intval($no->kode_urut) + 1);
            } else {
                $nomor = 1;
            }
            $data = [
                'uuid_barang'   => $uuid,
                'kode_parent'   => $cek->parent,
                'kode_utama'    => $request->subdata,
                'kode_urut'     => $nomor,
                'uraian'        => $cek->uraian,
                'nama_barang'   => $request->nama,
                'merek_barang'  => $request->merek,
                'type_barang'   => $request->tipe,
                'ukuran_barang' => $request->ukuran,
                'bahan'         => $request->bahan,
                'harga_beli'    => $request->harga,
                'tahun_beli'    => $request->tahun,
                'lokasi'        => $request->ruang,
                'kondisi_barang'=> $request->kondisi,
                'keterangan'    => $request->keterangan,
                'created_at'    => date('Y-m-d H:i:s'),
            ];

            $save = AsetData::insert($data);
            if ($save) {
                return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil di simpan!']);
            } else {
                return redirect()->back()->with(['status' => 'failed', 'message' => 'Data gagal di simpan!']);
            }
        } else {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Kategori tidak ditemukan']);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'uuid'      => 'required|string',
            'subadata'  => 'required|string',
            'nama'      => 'required|string',
            'merek'     => 'required|string',
            'harga'     => 'required|numeric',
            'tahun'     => 'required|numeric',
            'ruang'     => 'required|string',
            'kondisi'   => 'required|string',
        ]);

        $cek  = MasterSubdata::where('uuid_subdata', $request->subdata)->first();
        
        if ($cek) {
            $no   = AsetData::where('kode_utama', $request->subdata)->where('tahun_beli', $request->tahun)->orderBy('kode_urut', 'desc')->first();
            if ($no) {
                $nomor = (intval($no->kode_urut) + 1);
            } else {
                $nomor = 1;
            }
            $data = [
                'kode_parent'   => $cek->parent,
                'kode_utama'    => $request->subdata,
                // 'kode_urut'     => '',
                'uraian'        => $cek->uraian,
                'nama_barang'   => $request->nama,
                'merek_barang'  => $request->merek,
                'type_barang'   => $request->tipe,
                'ukuran_barang' => $request->ukuran,
                'bahan'         => $request->bahan,
                'harga_beli'    => $request->harga,
                'tahun_beli'    => $request->tahun,
                'lokasi'        => $request->ruang,
                'kondisi_barang'=> $request->kondisi,
                'keterangan'    => $request->keterangan,
                'updated_at'    => date('Y-m-d H:i:s'),
            ];

            $save = AsetData::where('uuid_barang', $request->uuid)->update($data);
            if ($save) {
                return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil di simpan!']);
            } else {
                return redirect()->back()->with(['status' => 'failed', 'message' => 'Data gagal di simpan!']);
            }
        } else {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Kategori tidak ditemukan']);
        }
    }

    public function get_number($data, $tahun)
    {
        if ($data) {
            return (intval($data->kode_urut) + 1);
        } elseif (!$data) {
            return 1;
        }
    }
}
