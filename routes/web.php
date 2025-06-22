<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KamarController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\SaranaController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\LaundryController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PerawatController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\profil\ProfilController;
use App\Http\Controllers\Pegawai\PengaduanController;
use App\Http\Controllers\Admin\PetugasLaundryController;
use App\Http\Controllers\Admin\PengaduanPegawaiController;
use App\Http\Controllers\Petugas\PetugasPengaduanController;
use App\Http\Controllers\Pegawai\PermintaanLaundryController;
use App\Http\Controllers\PetugasLaundry\AksiPetugasLaundryController;


Route::get('/', function () {
    return view('auth.login');
});
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Authentication
Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/keluar', [HomeController::class, 'keluar']);
Route::get('/admin/home', [HomeController::class, 'index']);
Route::get('/admin/change', [HomeController::class, 'change'])->name('change');
Route::post('/admin/change_password', [HomeController::class, 'change_password'])->name('change_password');


// Data Jabatan
Route::prefix('admin/jabatan')->middleware('cekLevel:1 ,2')->controller(JabatanController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::get('/detail/{id}', 'detail');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
    Route::post('/import', 'import');
    Route::get('/export', 'export');
    Route::get('/cetak', 'cetak');
});

// Data Admin
Route::prefix('admin/admin')->middleware('cekLevel:1, 2')->controller(AdminController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
    Route::post('/reset-password/{id}', 'resetPassword');
    Route::post('/import', 'import');
});


// Data Perawat
Route::prefix('admin/perawat')->middleware('cekLevel:1, 2')->controller(PerawatController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
    Route::post('/reset-password/{id}', 'resetPassword');
    Route::post('/import', 'import');
});

// Data Pegawai
Route::prefix('admin/pegawai')->middleware('cekLevel:1, 2')->controller(PegawaiController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
    Route::post('/reset-password/{id}', 'resetPassword');
    Route::post('/import', 'import');

});

// Data Petugas
Route::prefix('admin/petugas')->middleware('cekLevel:1, 2')->controller(PetugasController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
    Route::post('/reset-password/{id}', 'resetPassword');
    Route::post('/import', 'import');
});

// Data Petugas
Route::prefix('admin/petugaslaundry')->middleware('cekLevel:1,2')->controller(PetugasLaundryController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
    Route::post('/reset-password/{id}', 'resetPassword');
    Route::post('/import', 'import');
});

Route::prefix('admin/ruangan')->middleware('cekLevel:1, 2')->controller(RuanganController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
});

Route::prefix('admin/kamar')->middleware('cekLevel:1, 2')->controller(KamarController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
});


Route::prefix('admin/sarana')->middleware('cekLevel:1, 2')->controller(SaranaController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');

});


Route::prefix('admin/pengaduan')->middleware('cekLevel:1, 2')->controller(PengaduanPegawaiController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::post('/updatepengaduan/{id}', 'updatepetugas');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
    Route::get('/detail/{id}', 'detail');
    Route::get('/admin/pengaduan/mark-as-read', [PengaduanPegawaiController::class, 'markAsRead']);
    Route::post('/tolak/{id}', 'tolak');

});

Route::prefix('pegawai/pengaduan')->middleware('cekLevel:4')->controller(PengaduanController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::put('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
    Route::get('/detail/{id}', 'detail');
    Route::post('/rating/{id}', 'store');

});

Route::prefix('petugas/pengaduan')->middleware('cekLevel:3')->controller(PetugasPengaduanController::class)->group(function () {
    Route::get('/', 'read');
    Route::post('/aksipetugas/{id}', 'aksipetugas');
    Route::get('/aksi/{id}', 'aksi');
    Route::get('/delete/{id}', 'delete');
    Route::get('/detail/{id}', 'detail')->name('detail');

});

Route::prefix('admin/pasien')->middleware('cekLevel:1, 2, 6')->controller(PasienController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::get('/detail/{id}', 'detail');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
    Route::put('/panic-logs/{id}', 'updateStatusPanicLog')->name('panic-logs.updateStatusPanicLog');
    Route::get('/panic-button', 'panicButton');
    Route::get('/print-qr/{id}','printQR');
    Route::get('/print-qr-data/{id}','getQrData');
     // Tambahan route untuk AJAX get kamar berdasarkan ruangan
    Route::get('/get-kamar/{ruangan_id}', 'getKamar');
    Route::get('/get-kamar/{ruangan_id}/{pasien_id?}', 'getKamarEdit');
    Route::post('/status/{id}', 'updateStatus');

});
Route::get('admin/pasien/show-public/{id}', [PasienController::class, 'showPublic'])->name('pasien.showPublic');
Route::post('/pasien/laundry-request', [PasienController::class, 'ajukanLaundry'])->name('pasien.laundryRequest');
Route::post('/pasien/laundry-request', [PasienController::class, 'laundryRequest'])->name('pasien.laundryRequest');





Route::prefix('admin/laundry')->middleware('cekLevel: 1, 2')->controller(LaundryController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::get('/kirim/{id}', 'kirim');
    Route::get('/detail/{id}', 'detail');
});

Route::prefix('pegawai/laundry')->middleware('cekLevel:4')->controller(PermintaanLaundryController::class)->group(function () {
    Route::get('/', 'read');
    Route::get('/add', 'add');
    Route::post('/create', 'create');
    Route::get('/edit/{id}', 'edit');
    Route::post('/update/{id}', 'update');
    Route::get('/delete/{id}', 'delete');
    Route::get('/detail/{id}', 'detail');
});

Route::prefix('petugaslaundry/laundry')->middleware('cekLevel:5')->controller(AksiPetugasLaundryController::class)->group(function () {
    Route::get('/', 'read');
    Route::post('/pickup/{id}', 'pickup');
    Route::get('/jemput/{id}', 'jemput');
    Route::get('/selesai/{id}', 'selesai');
    Route::get('/diantar/{id}', 'diantar');
    Route::get('/detail/{id}', 'detail');
});



Route::prefix('profil')->middleware('cekLevel:1, 2, 3, 4, 5, 6')->controller(ProfilController::class)->group(function () {
    Route::get('/{id}', 'read')->name('profil.read');
    Route::get('/profile/{id}', 'index');
    Route::get('/edit/{id}', 'edit');
    Route::put('/update/{id}', 'update');
});
