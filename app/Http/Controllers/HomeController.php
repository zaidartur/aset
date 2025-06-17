<?php

namespace App\Http\Controllers;

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
        return view('main.home');
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
}
