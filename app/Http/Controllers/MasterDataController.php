<?php

namespace App\Http\Controllers;

use App\Imports\MasterImport;
use App\Imports\SubParameter;
use App\Models\Bahan;
use App\Models\MasterData;
use App\Models\MasterSubdata;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;

class MasterDataController extends Controller
{
    public function index()
    {
        $data = [
            'lists' => MasterData::all(),
        ];

        return view('main.parameter', $data);
    }

    public function subindex()
    {
        $data = [
            'lists'     => MasterSubdata::with(['isparam'])->get(),
            'params'    => MasterData::orderBy('id')->get(),
            'bahans'    => Bahan::all(),
        ];

        return view('main.subparameter', $data);
    }

    public function save(Request $request)
    {
        $request->validate([
            'kode'  => 'required|string',
            'nama'  => 'required|string',
        ]);

        $uuid = Uuid::uuid7()->toString();
        $data = [
            'uuid_aset'     => $uuid,
            'kode_aset'     => $request->kode,
            'uraian'        => $request->nama,
            'keterangan'    => $request->keterangan,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $save = MasterData::insert($data);

        if ($save) {
            return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil di simpan!']);
        } else {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Data gagal di simpan!']);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'uuid'  => 'required|string',
            'kode'  => 'required|string',
            'nama'  => 'required|string',
        ]);

        $data = [
            'kode_aset'     => $request->kode,
            'uraian'        => $request->nama,
            'keterangan'    => $request->keterangan,
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        $save = MasterData::where('uuid_aset', $request->uuid)->update($data);

        if ($save) {
            return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil di update!']);
        } else {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Data gagal di update!']);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'uuid'  => 'required|string',
        ]);

        $drop = MasterData::where('uuid_aset', $request->uuid)->delete();

        if ($drop) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'failed'];
        }
    }

    public function get_uid($uuid) 
    {
        $data = MasterData::where('uuid_aset', $uuid)->first();

        return $data;
    }

    public function import_data(Request $request)
    {
        $request->validate([
            'fileToUpload'  => 'required'
        ]);

        $file   = $request->file('fileToUpload');
        $ext    = $file->getClientOriginalExtension();
        if ($ext == 'xlsx' || $ext == 'xls') {
            $import = new MasterImport;
            Excel::import($import, $file->store('temp'));
            return ['res' => 'success', 'success' => $import->success, 'incomplete' => $import->incomplete, 'duplicate' => $import->duplicate, 'total' => $import->total];
        } else {
            return ['res' => 'failed'];
        }
    }

    public function serverside()
    {
        $request = Request();
        $draw = $_REQUEST['draw'] ?? 0;
        $row = $_REQUEST['start'] ?? 0;
        $rowperpage = $_REQUEST['length']; // Rows display per page
        $columnIndex = $_REQUEST['order'][0]['column']; // Column index
        $columnName = $_REQUEST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_REQUEST['order'][0]['dir']; // asc or desc
        $searchValue = $_REQUEST['search']['value']; // Search value

        $total  = MasterData::count();
        $query  = MasterData::whereNotNull('created_at');
        if (!empty($searchValue)) {
                $query->where('kode_aset', 'like', '%'. $searchValue . '%')
                    ->orWhere('uraian', 'like', '%'. $searchValue . '%')
                    ->orWhere('keterangan', 'like', '%'. $searchValue . '%');
        }
        $filter = $query->get();

        $data = [];
        foreach ($filter as $key => $value) {
            $data[] = [
                'uuid'          => $value->uuid_aset,
                'kode'          => $value->kode_aset,
                'uraian'        => $value->uraian,
                'keterangan'    => $value->keterangan,
                'opsi'          => '<button type="button" class="btn rounded-pill btn-icon btn-warning waves-effect waves-light" onclick="_edit(`'. $value->uuid_aset .'`)" title="Edit Parameter"><i class="ti ti-edit"></i></button>' .
                                    '&nbsp;&nbsp;' .
                                    '<button type="button" class="btn rounded-pill btn-icon btn-danger waves-effect waves-light" onclick="_delete(`'. $value->uuid_aset .'`)" title="Hapus Parameter"><i class="ti ti-trash"></i></button>'
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


    public function save_sub(Request $request)
    {
        $request->validate([
            'parent'    => 'required|string',
            'kode'      => 'required|string',
            'nama'      => 'required|string',
        ]);

        $uuid = Uuid::uuid7()->toString();
        $data = [
            'uuid_subdata'  => $uuid,
            'kode_subdata'  => $request->kode,
            'parent'        => $request->parent,
            'uraian'        => $request->nama,
            'keterangan'    => $request->keterangan,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $save = MasterSubdata::insert($data);

        if ($save) {
            return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil di simpan!']);
        } else {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Data gagal di simpan!']);
        }
    }

    public function update_sub(Request $request)
    {
        $request->validate([
            'uuid'      => 'required|string',
            'parent'    => 'required|string',
            'kode'      => 'required|string',
            'nama'      => 'required|string',
        ]);

        $data = [
            'kode_subdata'  => $request->kode,
            'parent'        => $request->parent,
            'uraian'        => $request->nama,
            'keterangan'    => $request->keterangan,
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        $save = MasterSubdata::where('uuid_subdata', $request->uuid)->update($data);

        if ($save) {
            return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil di update!']);
        } else {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Data gagal di update!']);
        }
    }

    public function delete_sub(Request $request)
    {
        $request->validate([
            'uuid'  => 'required|string',
        ]);

        $drop = MasterSubdata::where('uuid_subdata', $request->uuid)->delete();

        if ($drop) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'failed'];
        }
    }

    public function get_subuid($uuid) 
    {
        $data = MasterSubdata::where('uuid_subdata', $uuid)->first();

        return $data;
    }

    public function import_datasub(Request $request)
    {
        $request->validate([
            'fileToUpload'  => 'required'
        ]);

        $file   = $request->file('fileToUpload');
        $ext    = $file->getClientOriginalExtension();
        if ($ext == 'xlsx' || $ext == 'xls') {
            $import = new SubParameter;
            Excel::import($import, $file->store('temp'));
            return ['res' => 'success', 'success' => $import->success, 'incomplete' => $import->incomplete, 'duplicate' => $import->duplicate, 'total' => $import->total];
        } else {
            return ['res' => 'failed'];
        }
    }

    public function serverside_sub()
    {
        $request = Request();
        $draw = $_REQUEST['draw'] ?? 0;
        $row = $_REQUEST['start'] ?? 0;
        $rowperpage = $_REQUEST['length']; // Rows display per page
        $columnIndex = $_REQUEST['order'][0]['column']; // Column index
        $columnName = $_REQUEST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_REQUEST['order'][0]['dir']; // asc or desc
        $searchValue = $_REQUEST['search']['value']; // Search value

        $total  = MasterSubdata::count();
        $query  = DB::table('master_subdatas as ms')->leftJoin('master_data as md', 'ms.parent', '=', 'md.uuid_aset')
                ->select('ms.*', 'md.kode_aset', 'md.uraian as parameter');
        if (isset($request->kode) && !empty($request->kode)) {
            $query->where('ms.kode_subdata', 'like', '%'.$request->kode.'%');
        }
        if (isset($request->uraian) && !empty($request->uraian)) {
            $query->where('ms.uraian', 'like', '%'.$request->uraian.'%');
        }
        if (isset($request->keterangan) && !empty($request->keterangan)) {
            $query->where('ms.keterangan', 'like', '%'.$request->keterangan.'%');
        }
        $filter = $query->get();

        $data = [];
        foreach ($filter as $key => $value) {
            $data[] = [
                'uuid'          => $value->uuid_subdata,
                'kode'          => $value->kode_subdata,
                'parent'        => $value->kode_aset,
                'parameter'     => $value->parameter,
                'uraian'        => $value->uraian,
                'keterangan'    => $value->keterangan,
                'opsi'          => '<button type="button" class="btn rounded-pill btn-icon btn-warning waves-effect waves-light" onclick="_edit(`'. $value->uuid_subdata .'`)" title="Edit Data"><i class="ti ti-edit"></i></button>' .
                                    '&nbsp;&nbsp;' .
                                    '<button type="button" class="btn rounded-pill btn-icon btn-danger waves-effect waves-light" onclick="_delete(`'. $value->uuid_subdata .'`)" title="Hapus Data"><i class="ti ti-trash"></i></button>'
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
