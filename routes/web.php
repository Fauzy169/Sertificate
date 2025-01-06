<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CertificateController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(CertificateController::class)->group(function()
{
    Route::get('/certif', 'index');
    Route::get('/view-certificate','viewcertificate')->name('viewCertificate');
    Route::get('/download-certificate','downloadCertificate')->name('downloadCertificate');
}
);