<?php

declare(strict_types=1);

use App\Livewire\Tenant\Pages\CorporateInfo\BusinessAddress;
use App\Livewire\Tenant\Pages\CorporateInfo\BusinessNature;
use App\Livewire\Tenant\Pages\CorporateInfo\CompanyAddress;
use App\Livewire\Tenant\Pages\CorporateInfo\Director;
use App\Livewire\Tenant\Pages\CorporateInfo\Shareholder;
use App\Livewire\Tenant\Pages\CorporateInfo\YearEnd;
use App\Livewire\Tenant\Pages\CorporateInfo\CompanyName;
use App\Livewire\Tenant\Pages\CorporateInfo\ShareCapital;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\ScopeSessions;

use App\Livewire\Shared\Auth\Login;
use App\Livewire\Tenant\Pages\Dashboard;
use App\Http\Controllers\AuthController;
use App\Livewire\Tenant\Pages\User\IndexUser;
use App\Livewire\Tenant\Pages\User\CreateUser;
use App\Livewire\Tenant\Pages\User\ShowUser;
use App\Livewire\Tenant\Pages\AuditPartner\IndexAuditPartner;
use App\Livewire\Tenant\Pages\AuditPartner\CreateAuditPartner;
use App\Livewire\Tenant\Pages\AuditPartner\ShowAuditPartner;
use App\Livewire\Tenant\Pages\AuditFirm\ShowAuditFirm;
use App\Livewire\Tenant\Pages\Company\CreateCompany;
use App\Livewire\Tenant\Pages\Company\ShowCompany;
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

        Route::group(['prefix' => 'audit-partners', 'as' => 'auditpartners.'], function () {
            Route::get('/', IndexAuditPartner::class)->name('index');
            Route::get('/create', CreateAuditPartner::class)->name('create');
            Route::get('/{id}', ShowAuditPartner::class)->name('show');
        });

        Route::get('/audit-firm', ShowAuditFirm::class)->name('auditfirm.show');

        Route::group(['prefix' => 'companies', 'as' => 'companies.'], function () {
            // Route::get('/', IndexCompany::class)->name('index');
            Route::get('/create', CreateCompany::class)->name('create');
            Route::get('/{id}', ShowCompany::class)->name('show');

            // Route::get('/{id}', CorporateInfo::class)->name('corporate');
        });

        Route::group(['prefix' => 'companies/{id}/corporate-info', 'as' => 'corporate.'], function () {
            // Redirect '/companies/{id}/corporate-info/' to '/companies/{id}/corporate-info/year-end'
            Route::redirect('/', 'year-end')->name('index');

            // Serve the YearEnd Livewire component
            Route::get('/year-end', YearEnd::class)->name('yearend');
            Route::get('/company-name', CompanyName::class)->name('companyname');
            Route::get('/business-nature', BusinessNature::class)->name('businessnature');
            Route::get('/company-address', CompanyAddress::class)->name('companyaddress');
            Route::get('/business-address', BusinessAddress::class)->name('businessaddress');
            Route::get('/share-capital', ShareCapital::class)->name('sharecapital');
            Route::get('/directors', Director::class)->name('directors');
            Route::get('/shareholders', Shareholder::class)->name('shareholders');
            Route::get('/secretaries', YearEnd::class)->name('secretaries');
            Route::get('/auditor', YearEnd::class)->name('auditor');
            Route::get('/charges', YearEnd::class)->name('charges');
            Route::get('/dividends', YearEnd::class)->name('dividends');
            Route::get('/report-info', YearEnd::class)->name('reportinfo');
        });
    });
});
