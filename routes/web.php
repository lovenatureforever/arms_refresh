<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\AdminDashboard;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\MainDashboard;
use App\Livewire\Dashboard\SuperAdminDashboard;
use App\Livewire\Tenant\CreateTenant;
use App\Livewire\Tenant\IndexTenant;
use App\Livewire\Tenant\ShowTenant;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', MainDashboard::class)->name('home');
    Route::prefix('superadmin')->group(function () {

        Route::prefix('tenants')->group(function () {
            Route::get('/', IndexTenant::class)->name('index.tenant');
            Route::get('/create', CreateTenant::class)->name('create.tenant');
            Route::get('/{id}', ShowTenant::class)->name('show.tenant');
        });
    });
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
