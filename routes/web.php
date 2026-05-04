<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; // [PEMBARUAN] Import DashboardController
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\OutboundController; 
use App\Http\Controllers\ReportController;   

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini adalah tempat untuk mendaftarkan rute web untuk aplikasi Anda.
| Rute-rute ini dimuat oleh RouteServiceProvider dan semuanya akan
| dimasukkan ke dalam grup middleware "web".
|
*/

/**
 * Rute untuk Tamu (Guest)
 * User yang belum login hanya bisa mengakses halaman login.
 */
Route::middleware('guest')->group(function () { 
    
    // Menampilkan halaman awal (form login) saat mengakses URL root '/'
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login'); 
    
    // Rute POST untuk memproses data form login yang disubmit
    Route::post('/', [AuthController::class, 'login'])->name('login.post'); 

});

/**
 * Rute yang Membutuhkan Autentikasi (Auth)
 * User harus login terlebih dahulu untuk mengakses rute di dalam grup ini.
 */
Route::middleware('auth')->group(function () { 
    
    // [PEMBARUAN] Halaman Dashboard Utama kini menggunakan Controller agar bisa mengirim data dinamis
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 

    // Rute POST untuk memproses fitur logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); 

    // ==========================================================
    // FITUR MASTER BARANG (ITEM MANAGEMENT)
    // ==========================================================
    
    // Menampilkan daftar semua barang yang telah disimpan
    Route::get('/items', [ItemController::class, 'index'])->name('items.index'); 

    // Menampilkan form untuk menambahkan barang baru dan aturan konversinya
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create'); 
    
    // Menyimpan data barang baru beserta detail konversi ke database
    Route::post('/items', [ItemController::class, 'store'])->name('items.store'); 

    // Menampilkan form edit untuk barang spesifik
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit'); 

    // Menyimpan perubahan data barang (Update)
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update'); 

    // Menghapus data barang beserta konversinya
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy'); 
    

    // ==========================================================
    // FITUR BARANG MASUK (INBOUND TRANSACTIONS)
    // ==========================================================
    
    // Menampilkan form pencatatan barang masuk
    Route::get('/inbounds/create', [InboundController::class, 'create'])->name('inbounds.create'); 
    
    // Menyimpan data transaksi barang masuk ke database
    Route::post('/inbounds', [InboundController::class, 'store'])->name('inbounds.store'); 


    // ==========================================================
    // FITUR BARANG KELUAR (OUTBOUND TRANSACTIONS)
    // ==========================================================
    
    // Menampilkan form pencatatan barang keluar
    Route::get('/outbounds/create', [OutboundController::class, 'create'])->name('outbounds.create'); 
    
    // Menyimpan data transaksi barang keluar ke database (dengan validasi stok)
    Route::post('/outbounds', [OutboundController::class, 'store'])->name('outbounds.store'); 


    // ==========================================================
    // FITUR LAPORAN (REPORTS)
    // ==========================================================
    
    // Menampilkan halaman laporan sisa stok real-time
    Route::get('/reports/stock', [ReportController::class, 'index'])->name('reports.stock'); 

    // Rute untuk memproses dan mengunduh data stok menjadi file Excel
    Route::get('/reports/stock/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export_excel');

    // Menampilkan Laporan Pergerakan Barang (Kartu Stok)
    Route::get('/reports/movement', [ReportController::class, 'movement'])->name('reports.movement');

});