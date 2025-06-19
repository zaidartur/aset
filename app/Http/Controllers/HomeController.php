<?php

namespace App\Http\Controllers;

use App\Models\AsetData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $years = AsetData::groupBy('tahun_beli')->select('tahun_beli')->orderBy('tahun_beli', 'desc')->limit(3)->get();
        $countyear = [];
        foreach ($years as $key => $value) {
            $countyear[] = [
                'tahun'     => $value->tahun_beli,
                'jumlah'    => AsetData::where('tahun_beli', $value->tahun_beli)->count(),
            ];
        }
        $before = AsetData::where('tahun_beli', (date('Y') - 1))->count();
        $total  = AsetData::count();

        $data = [
            'total'     => $total,
            'widget'    => $countyear,
            'diff'      => (($total-$before)),
            'charts'    => $this->sebaran(),
            'cond'      => $this->kondisi(),
        ];

        return view('main.home', $data);
    }

    public function profile()
    {
        $data = [
            'lists' => User::select('id', 'uuid', 'name', 'email', 'created_at', 'updated_at')->orderBy('id')->get(),
        ];
        return view('main.profile', $data);
    }

    public function user_list()
    {
        $data = [];
        return view('main.setting', $data);
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'uuid'      => 'required|string',
            'name'      => 'required|string',
        ]);
        
        $data = [
            'name'      => $request->name,
        ];
        if (!empty($request->password) && !empty($request->password_confirm)) {
            $data += [
                'password'  => Hash::make($request->password),
            ];
        }

        $save = User::where('uuid', $request->uuid)->update($data);
        if ($save) {
            if (!empty($request->password) && !empty($request->password_confirm)) {
                $pwd = 'true';
            } else {
                $pwd = 'false';
            }
            return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil di simpan!', 'password' => $pwd]);
        } else {
            return redirect()->back()->with(['status' => 'failed', 'message' => 'Data gagal di simpan!']);
        }
    }

    public function user_save(Request $request)
    {
        //
    }

    public function user_update(Request $request)
    {
        //
    }

    public function user_delete(Request $request)
    {
        //
    }

    public function sebaran()
    {
        $locate = AsetData::groupBy('lokasi')->select('lokasi')->orderBy('lokasi')->get();

        if (count($locate) > 0) {
            $data = [];
            $total = 0;
            foreach ($locate as $key => $value) {
                $count  = AsetData::where('lokasi', $value->lokasi)->count();
                $total  = $total + $count;
                
                $data[] = [
                    'label'     => $value->lokasi,
                    'value'     => $count,
                ];
            }

            return ['total' => $total, 'data' => $data];
        } else {
            return [];
        }
    }

    public function kondisi()
    {
        $baik   = AsetData::where('kondisi_barang', 'b')->count();
        $ringan = AsetData::where('kondisi_barang', 'rr')->count();
        $berat  = AsetData::where('kondisi_barang', 'rb')->count();
        $total  = AsetData::count();

        return ['baik' => $baik, 'ringan' => $ringan, 'berat' => $berat, 'jumlah' => $total];
    }
}
