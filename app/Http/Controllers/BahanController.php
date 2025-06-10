<?php

namespace App\Http\Controllers;

use App\Imports\BahanImport;
use App\Models\Bahan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;

class BahanController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string',
        ]);

        $data = [
            'uuid_bahan'    => Uuid::uuid7()->toString(),
            'nama_bahan'    => $request->nama,
            'keterangan'    => $request->keterangan,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $save = Bahan::insert($data);
        if ($save) {
            return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil di simpan!']);
        } else {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Data gagal di simpan!']);
        }
    }

    public function detail($uid)
    {
        $res = Bahan::where('uuid_bahan', $uid)->first();

        return $res;
    }

    public function update(Request $request)
    {
        $request->validate([
            'uuid'  => 'required|string',
            'nama'  => 'required|string',
        ]);

        $data = [
            'nama_bahan'    => $request->nama,
            'keterangan'    => $request->keterangan,
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        $save = Bahan::where('uuid_barang', $request->uuid)->update($data);
        if ($save) {
            return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil di simpan!']);
        } else {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Data gagal di simpan!']);
        }
    }

    public function delete(Request $request)
    {
        $request->validate(['uuid' => 'required|string']);

        $del = Bahan::where('uuid_bahan', $request->uuid)->delete();
        
        if ($del) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'failed'];
        }
    }

    public function import_data(Request $request)
    {
        $request->validate([
            'fileToUpload'  => 'required'
        ]);

        $file   = $request->file('fileToUpload');
        $ext    = $file->getClientOriginalExtension();
        if ($ext == 'xlsx' || $ext == 'xls') {
            $import = new BahanImport;
            Excel::import($import, $file->store('temp'));
            // return ['res' => 'success', 'success' => $import->success, 'incomplete' => $import->incomplete, 'duplicate' => $import->duplicate, 'total' => $import->total];
        } else {
            return ['res' => 'failed'];
        }
    }
}
