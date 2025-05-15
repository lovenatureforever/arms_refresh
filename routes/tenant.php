<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\ScopeSessions;

use App\Livewire\Shared\Auth\Login;
use App\Livewire\Tenant\Pages\Dashboard;
use App\Http\Controllers\AuthController;
use App\Livewire\Tenant\Pages\Users\IndexUser;
use App\Livewire\Tenant\Pages\Users\CreateUser;
use App\Livewire\Tenant\Pages\Users\ShowUser;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    ScopeSessions::class,
])->group(function () {
    Route::get('/', Login::class)->name('login');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/identity', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/home', Dashboard::class)->name('home');
        // Route::prefix('users')->group(function () {
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::get('/', IndexUser::class)->name('index');
            Route::get('/create', CreateUser::class)->name('create');
            Route::get('/{id}', ShowUser::class)->name('show');
        });
    });
});
