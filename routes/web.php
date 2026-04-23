<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\{
    DashboardController,
};

use App\Http\Controllers\superadmin\{
    DashboardSuperAdminController,
    ApiWhatsappController,
    ManageTestimoniController,
    ManagePelangganController,
    ProfilController as ProfilSuperAdminController,
    BrandController,
    KategoriProdukController,
    ProdukController,
    PesananController,
    LaporanController,
    APIMidtransController,
};
use App\Http\Controllers\user\{
    IndexController,
    DaftarProdukController,
    KeranjangController,
    TransaksiController,
    RiwayatPesananController,
    ProfilUserController as ProfilUserController,
};
use App\Http\Controllers\auth\{
    LoginController,
    RegisterController,
    GoogleController,
    ForgotPasswordController,
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/run-superadmin', function () {
    Artisan::call('db:seed', [
        '--class' => 'SuperadminSeeder'
    ]);

    return "SuperAdminSeeder has been create successfully!";
});

// Manual
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);



Route::group(['middleware' => ['role:superadmin']], function () {
    Route::get('/profil-superadmin', [ProfilSuperAdminController::class, 'index'])->name('profil-superadmin');
    Route::put('/profil-superadmin/update', [ProfilSuperAdminController::class, 'update'])->name('profil-superadmin.update');
    Route::get('/dashboard-superadmin', [DashboardSuperAdminController::class, 'index'])->name('dashboard-superadmin');
    Route::resource('kategori-produk', KategoriProdukController::class);
    Route::resource('produk', ProdukController::class);
    Route::resource('pesanan', PesananController::class);
    Route::resource('api-midtrans', APIMidtransController::class);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
});




Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/katalog', [DaftarProdukController::class, 'index'])->name('katalog.index');
Route::get('/katalog/{id}', [DaftarProdukController::class, 'show'])->name('katalog.show');

Route::post('/midtrans/callback', [TransaksiController::class, 'callbackMidtrans']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/profil', [ProfilUserController::class, 'index'])->name('profil.index');
    Route::post('/profil/update', [ProfilUserController::class, 'update'])->name('profil.update');

    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/add/{id}', [KeranjangController::class, 'add'])->name('keranjang.add');
    Route::delete('/keranjang/remove/{id}', [KeranjangController::class, 'remove'])->name('keranjang.remove');

    Route::get('/transaksi/summary', [TransaksiController::class, 'summary'])->name('transaksi.summary');
    Route::post('/transaksi/checkout', [TransaksiController::class, 'checkout'])->name('transaksi.checkout');
    Route::post('/transaksi/success-frontend', [TransaksiController::class, 'successFrontend'])->name('transaksi.success_frontend');

    Route::get('/riwayat-pesanan', [RiwayatPesananController::class, 'index'])->name('riwayat-pesanan.index');
});
