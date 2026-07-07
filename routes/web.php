<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\SatuanLogistikController;

// Route uji coba untuk menembak API Python berdasarkan ID Cabang
Route::get('/test-rop/{cabangId}', [SatuanLogistikController::class, 'prediksiRop']);