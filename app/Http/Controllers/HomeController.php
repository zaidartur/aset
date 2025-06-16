<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $data = [];
        return view('main.profile', $data);
    }

    public function user_list()
    {
        $data = [];
        return view('main.setting', $data);
    }

    public function update_profile(Request $request)
    {
        //
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
