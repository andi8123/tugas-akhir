<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterPengajuanController;
use App\Http\Controllers\MasterRoleController;
use App\Http\Controllers\MasterSuratController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/**
 * ! Jangan ubah route yang ada dalam group ini
 * */
Route::controller(AuthController::class)
    ->group(function () {
        Route::get('/', 'checkToken')->name('check');
        Route::get('/logout', 'logout')->name('logout'); // gunakan untuk logout
        Route::get('/roles', 'changeUserRole')->middleware('auth.token');
    });

/**
 * ! Jadikan route di bawah sebagai halaman utama dari web
 * ! harap tidak mengubah nilai pada name();
 */
Route::middleware('auth.token')->group(function () {
    Route::get('/dashboard', [UserController::class, 'adminDashboard'])->name('dashboard');

    Route::get('/generate-surat/{pengajuanId}', [PengajuanController::class, 'generatePDF']);
});

Route::middleware(['auth.token', 'auth.admin'])->group(function () {
    Route::group(['prefix' => 'master-surat'], function () {
        Route::get('/', [MasterSuratController::class, 'index'])->name('master-surat.index');
        Route::post('/', [MasterSuratController::class, 'store'])->name('master-surat.store');
        Route::get('/add', [MasterSuratController::class, 'add'])->name('master-surat.add');
        Route::get('/edit/{id}', [MasterSuratController::class, 'edit'])->name('master-surat.edit');
        Route::put('/edit/{id}', [MasterSuratController::class, 'update'])->name('master-surat.update');
        Route::delete('/delete/{id}', [MasterSuratController::class, 'destroy'])->name('master-surat.destroy');
    });

    Route::group(['prefix' => 'master-role'], function () {
        Route::get('/', [MasterRoleController::class, 'index'])->name('master-role.index');
        Route::get('/add', [MasterRoleController::class, 'add'])->name('master-role.add');
        Route::get('/edit', [MasterRoleController::class, 'edit'])->name('master-role.edit');
        Route::delete('/delete', [MasterRoleController::class, 'delete'])->name('master-role.delete');
    });

    Route::group(['prefix' => 'master-pengajuan'], function () {
        Route::get('/', [MasterPengajuanController::class, 'index'])->name('master-pengajuan.index');
        Route::post('/', [MasterPengajuanController::class, 'store'])->name('master-pengajuan.store');
        Route::get('/add', [MasterPengajuanController::class, 'add'])->name('master-pengajuan.add');
        Route::get('/edit/{id}', [MasterPengajuanController::class, 'edit'])->name('master-pengajuan.edit');
        Route::put('/edit/{id}', [MasterPengajuanController::class, 'update'])->name('master-pengajuan.update');
        Route::delete('/delete/{id}', [MasterPengajuanController::class, 'destroy'])->name('master-pengajuan.delete');
    });

    
});

Route::get('/users', function () {
    return "Users Page";
})->name('users.index');

// untuk user
Route::group(['prefix' => 'pengajuan-user'], function () {
    Route::get('/', [UserController::class, 'indexPengajuan'])->name('user.pengajuan.index');
    Route::get('/add', [UserController::class, 'addPengajuanIndex'])->name('data-pengajuan.tambahIndex');
    Route::get('/add/{id}', [UserController::class, 'addPengajuan'])->name('data-pengajuan.tambah');
    Route::post('/add', [UserController::class, 'storePengajuan'])->name('user.pengajuan.store');
    Route::get('/{id}', [UserController::class, 'showPengajuan'])->name('user.pengajuan.show');
});

Route::get('/dashboard-user', [PengajuanController::class, 'indexUser'])->name('dashboard.user');
// end test


Route::group(['prefix' => 'pengajuan'], function () {
    Route::get('/', [PengajuanController::class, 'index'])->name('data-pengajuan.index');        
    Route::post('/', [PengajuanController::class, 'store'])->name('data-pengajuan.store');
    Route::get('/{id}', [PengajuanController::class, 'show'])->name('data-pengajuan.show');
    Route::get('/add', [PengajuanController::class, 'add'])->name('data-pengajuan.add');
    Route::post('/add-nomor-surat/{id}', [PengajuanController::class, 'addNomorSurat'])->name('data-pengajuan.add-nomor-surat');
    Route::get('/edit/{id}', [PengajuanController::class, 'edit'])->name('data-pengajuan.edit');
    Route::put('/edit/{id}', [PengajuanController::class, 'update'])->name('data-pengajuan.update');
    Route::post('/verifikasi/{id}', [PengajuanController::class, 'verifikasi'])->name('data-pengajuan.verifikasi');
    Route::delete('/delete/{id}', [PengajuanController::class, 'destroy'])->name('data-pengajuan.delete');
    Route::get('/data-pengajuan/cetak-pdf', [PengajuanController::class, 'cetakPDF'])->name('data-pengajuan.cetak-pdf');
});

/**
 * * Buat route-route baru di bawah ini
 * * Pastikan untuk selalu menggunakan middleware('auth.token')
 * * middleware tersebut digunakan untuk verifikasi access pengguna dengan web
 *
 * * Bisa juga ditambahkan dengan middleware lainnya.
 * * Berikut adalah beberapa middleware lain yang telah tersedia,
 * * dapat digunakan untuk mengatur akses route berdasarkan role user
 *
 * 1.) auth.admin -> biasa digunakan untuk akses route untuk manage user lain
 * 2.) auth.mahasiswa -> akses route untuk user dengan role mahasiswa
 * 3.) auth.dosen -> akses route untuk user dengan role dosen
 * 4.) auth.developer -> akses route untuk user developer
 *
 * ? contoh penggunaan: middleware(['auth.token', 'auth.mahasiswa'])
 */
