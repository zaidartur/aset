<?php

use App\Http\Controllers\AsetDataController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('/tabel-aset')->middleware(['auth'])->group(function() {
    Route::get('/', [AsetDataController::class, 'index'])->name('aset');
    Route::get('/detail/{uid}', [AsetDataController::class, 'detail'])->name('aset.detail');
    Route::get('/subdata/{uid}', [AsetDataController::class, 'list_of_sub']);
    Route::get('/server-side', [AsetDataController::class, 'serverside'])->name('aset.ss');

    Route::post('/save-data', [AsetDataController::class, 'save'])->name('aset.save');
    Route::post('/update-data', [AsetDataController::class, 'update'])->name('aset.update');
    Route::post('/hapus-data', [AsetDataController::class, 'delete'])->name('aset.drop');
    Route::post('/import-data', [AsetDataController::class, 'import_data'])->name('aset.import');
    Route::post('/get-number', [AsetDataController::class, 'autonumber'])->name('aset.number');
});

Route::prefix('/parameter')->middleware(['auth'])->group(function() {
    Route::get('/', [MasterDataController::class, 'index'])->name('parameter');
    Route::get('/detail-parameter/{uid}', [MasterDataController::class, 'get_uid'])->name('parameter.first');
    Route::get('/server-side', [MasterDataController::class, 'serverside'])->name('parameter.ss');

    Route::post('/save-data', [MasterDataController::class, 'save'])->name('parameter.save');
    Route::post('/update-data', [MasterDataController::class, 'update'])->name('parameter.update');
    Route::post('/delete-data', [MasterDataController::class, 'delete'])->name('parameter.drop');
    Route::post('/import-data', [MasterDataController::class, 'import_data'])->name('parameter.import');
});

Route::prefix('/sub-parameter')->middleware(['auth'])->group(function() {
    Route::get('/', [MasterDataController::class, 'subindex'])->name('subparameter');
    Route::get('/detail-sub-parameter/{uid}', [MasterDataController::class, 'get_subuid'])->name('subparameter.first');
    Route::get('/server-side', [MasterDataController::class, 'serverside_sub'])->name('subparameter.ss');

    Route::post('/save-data', [MasterDataController::class, 'save_sub'])->name('subparameter.save');
    Route::post('/update-data', [MasterDataController::class, 'update_sub'])->name('subparameter.update');
    Route::post('/delete-data', [MasterDataController::class, 'delete_sub'])->name('subparameter.drop');
    Route::post('/import-data', [MasterDataController::class, 'import_datasub'])->name('subparameter.import');
});

Route::prefix('/report')->middleware(['auth'])->group(function() {
    Route::get('/aset', [ReportController::class, 'index'])->name('report.aset');
    Route::get('/label', [ReportController::class, 'labeling'])->name('report.label');
    Route::get('/ekspor-aset', [ReportController::class, 'export'])->name('report.aset.export');
    Route::get('/print-aset', [ReportController::class, 'print_aset'])->name('report.aset.print');
    Route::get('/server-side', [ReportController::class, 'serverside'])->name('report.aset.ss');
    Route::get('/server-side-label', [ReportController::class, 'serverside_label'])->name('report.label.ss');
});
// Route::prefix('/master-bahan')->middleware(['auth'])->group(function() {
//     Route::get('/detail-bahan/{uid}', [BahanController::class, 'detail'])->name('bahan.detail');

//     Route::post('/save-data', [BahanController::class, 'save'])->name('bahan.save');
//     Route::post('/update-data', [BahanController::class, 'update'])->name('bahan.update');
//     Route::post('/hapus-data', [BahanController::class, 'delete'])->name('bahan.drop');
//     Route::post('/import-data', [BahanController::class, 'import_data'])->name('bahan.import');
// });

Route::prefix('/profile')->middleware(['auth'])->group(function() {
    Route::get('/', [HomeController::class, 'profile'])->name('profile');

    Route::post('/update-profile', [HomeController::class, 'update_profile'])->name('profile.update');
});

Route::prefix('/users')->middleware(['auth'])->group(function() {
    Route::get('/', [HomeController::class, 'user_list'])->name('users');

    Route::post('/save', [HomeController::class, 'user_save'])->name('users.save');
    Route::get('/update', [HomeController::class, 'user_update'])->name('users.update');
    Route::get('/delete', [HomeController::class, 'user_delete'])->name('users.delete');
});

// Route::prefix('/pengaturan')->middleware(['auth'])->group(function() {
//     Route::get('/', [SettingController::class, 'pengaturan'])->name('setting');
// });