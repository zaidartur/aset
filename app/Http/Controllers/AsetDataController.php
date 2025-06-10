<?php

namespace App\Http\Controllers;

use App\Models\AsetData;
use App\Models\MasterSubdata;
use Illuminate\Http\Request;

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
}
