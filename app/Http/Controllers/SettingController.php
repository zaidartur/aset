<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function pengaturan()
    {
        $data = [
            'bahans'   => Bahan::all(),
        ];

        return view('main.setting', $data);
    }
}
