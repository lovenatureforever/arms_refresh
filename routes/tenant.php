<?php

declare(strict_types=1);

use App\Livewire\Tenant\Pages\CorporateInfo\Auditor;
use App\Livewire\Tenant\Pages\CorporateInfo\BusinessAddress;
use App\Livewire\Tenant\Pages\CorporateInfo\BusinessNature;
use App\Livewire\Tenant\Pages\CorporateInfo\Charges;
use App\Livewire\Tenant\Pages\CorporateInfo\CompanyAddress;
use App\Livewire\Tenant\Pages\CorporateInfo\Dividends;
use App\Livewire\Tenant\Pages\CorporateInfo\Director;
use App\Livewire\Tenant\Pages\CorporateInfo\ReportInfo;
use App\Livewire\Tenant\Pages\CorporateInfo\Secretaries;
use App\Livewire\Tenant\Pages\CorporateInfo\Shareholder;
use App\Livewire\Tenant\Pages\CorporateInfo\YearEnd;
use App\Livewire\Tenant\Pages\CorporateInfo\CompanyName;
use App\Livewire\Tenant\Pages\CorporateInfo\ShareCapital;
use App\Livewire\Tenant\Pages\Cosec\AdminCosecCredit;
use App\Livewire\Tenant\Pages\Cosec\AdminCosecIndex;
use App\Livewire\Tenant\Pages\Cosec\AdminCosecReport;
use App\Livewire\Tenant\Pages\Cosec\AdminCosecService;
use App\Livewire\Tenant\Pages\Cosec\AdminCosecTemplate;
use App\Livewire\Tenant\Pages\Cosec\AdminCosecSignature;
use App\Livewire\Tenant\Pages\Cosec\AdminCosecOrderEdit;
use App\Livewire\Tenant\Pages\Cosec\CartCosec;
use App\Livewire\Tenant\Pages\Cosec\DirectorSignatures;
use App\Livewire\Tenant\Pages\Cosec\IndexCosec;
use App\Livewire\Tenant\Pages\Cosec\PlaceOrderCosec;
use App\Livewire\Tenant\Pages\Cosec\OrderCosec;
use App\Livewire\Tenant\Pages\Cosec\ViewCosec;
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
use App\Livewire\Tenant\Pages\v2\Company\CreateCompany;
use App\Livewire\Tenant\Pages\Company\EditCompany;
use App\Livewire\Tenant\Pages\Company\ShowCompany;
use App\Livewire\Tenant\Pages\Report\DataImport;
use App\Http\Controllers\Tenant\CompanyReportController;
use App\Http\Middleware\IsAdmin;
use App\Livewire\Tenant\Pages\Report\DataMigration;
use App\Livewire\Tenant\Pages\Report\DataMigration\SofpDataMigration;
use App\Livewire\Tenant\Pages\Report\DataMigration\SociDataMigration;
use App\Livewire\Tenant\Pages\Report\DataMigration\SoceDataMigration;
use App\Livewire\Tenant\Pages\Report\DataMigration\SocfDataMigration;
use App\Livewire\Tenant\Pages\Report\DataMigration\StsooDataMigration;
use App\Livewire\Tenant\Pages\Report\DataMigration\NtfsDataMigration;
use App\Livewire\Tenant\Pages\Report\NtfsMigration;
use App\Livewire\Tenant\Pages\ReportConfig\DirectorReportConfig;
use App\Livewire\Tenant\Pages\ReportConfig\NtfsConfig;
use App\Livewire\Tenant\Pages\UserProfile;
use App\Livewire\Tenant\Pages\UserCreditHistory;
use App\Livewire\Tenant\Pages\Cosec\AdminCosecCreditHistory;
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

        // User Profile routes
        Route::get('/profile', UserProfile::class)->name('profile');
        Route::get('/profile/credit-history', UserCreditHistory::class)->name('profile.credit-history');

        // Route::prefix('users')->group(function () {
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::get('/', IndexUser::class)->name('index');
            Route::get('/create', CreateUser::class)->name('create');
            Route::get('/{id}', ShowUser::class)->name('show');
            Route::get('/{id}/credit-history', \App\Livewire\Tenant\Pages\User\CreditHistory::class)->name('credit-history');
        });

        Route::group(['prefix' => 'audit-partners', 'as' => 'auditpartners.'], function () {
            Route::get('/', IndexAuditPartner::class)->name('index');
            Route::get('/create', CreateAuditPartner::class)->name('create');
            Route::get('/{id}', ShowAuditPartner::class)->name('show');
        });

        Route::get('/audit-firm', ShowAuditFirm::class)->name('auditfirm.show');

        Route::group(['prefix' => 'companies', 'as' => 'companies.'], function () {
            // Route::get('/', IndexCompany::class)->name('index');
            Route::get('/create', CreateCompany::class)->name('create')->middleware('user.type:admin,subscriber');
            Route::get('/{id}', ShowCompany::class)->name('show');
            Route::get('/{id}/edit', EditCompany::class)->name('edit');
            Route::get('/{id}/directors', \App\Livewire\Tenant\Pages\Company\DirectorCreate::class)
                ->name('directors')
                ->middleware('user.type:admin,subscriber');

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
            Route::get('/secretaries', Secretaries::class)->name('secretaries');
            Route::get('/auditor', Auditor::class)->name('auditor');
            Route::get('/charges', Charges::class)->name('charges');
            Route::get('/dividends', Dividends::class)->name('dividends');
            Route::get('/report-info', ReportInfo::class)->name('reportinfo');
        });

        Route::group(['prefix' => 'companies/{id}/report-config', 'as' => 'reportconfig.'], function () {
            Route::get('/director', DirectorReportConfig::class)->name('director');
            Route::get('/ntfs', NtfsConfig::class)->name('ntfs');
        });

        Route::get('/companies/{id}/reports', DataImport::class)->name('datamigration.index');
        Route::get('/reports/{id}/download-excel', [CompanyReportController::class, 'downloadExcel'])->name('downloadexcel');
        Route::get('/reports/{id}/generate', [CompanyReportController::class, 'viewFinancialReport'])->name('reportpdf');
        Route::get('/reports/test', [CompanyReportController::class, 'test'])->name('test');
        // Data Migration Tabs
        Route::get('/reports/{id}/migration/sofp', SofpDataMigration::class)->name('datamigration.sofp');
        Route::get('/reports/{id}/migration/soci', SociDataMigration::class)->name('datamigration.soci');
        Route::get('/reports/{id}/migration/soce', SoceDataMigration::class)->name('datamigration.soce');
        Route::get('/reports/{id}/migration/socf', SocfDataMigration::class)->name('datamigration.socf');
        Route::get('/reports/{id}/migration/stsoo', StsooDataMigration::class)->name('datamigration.stsoo');
        Route::get('/reports/{id}/migration/ntfs', NtfsDataMigration::class)->name('datamigration.ntfs');
        // Optionally, keep the old route for backward compatibility
        Route::get('/reports/{id}/migration', DataMigration::class)->name('datamigration');
        Route::get('/reports/{report}/migration/ntfs/{id}', NtfsMigration::class)->name('datamigration.ntfsdetail');
        Route::get('/reports/{id}', [CompanyReportController::class, 'viewFinancialReport'])->name('financialreport');


        // Admin COSEC routes (accessible by admin and subscriber only)
        Route::prefix('/admin/cosec')->middleware(['user.type:admin,subscriber'])->group(function () {
            Route::get('/', AdminCosecIndex::class)->name('admin.cosec.index');
            // Route::get('/{id}', AdminCosecIndex::class)->name('admin.cosec.show');
            Route::get('/report/{id}', AdminCosecReport::class)->name('admin.cosec.report');
            Route::get('/order/edit/{id}', AdminCosecOrderEdit::class)->name('admin.cosec.order.edit');
            Route::get('/services', AdminCosecService::class)->name('admin.cosec.services');
            Route::get('/templates', AdminCosecTemplate::class)->name('admin.cosec.templates');
            Route::get('/templates/create', \App\Livewire\Tenant\Pages\Cosec\CreateCosecTemplate::class)->name('admin.cosec.templates.create')->middleware('user.type:admin,subscriber');
            Route::get('/templates/edit/{id}', \App\Livewire\Tenant\Pages\Cosec\CreateCosecTemplate::class)->name('admin.cosec.templates.edit')->middleware('user.type:admin,subscriber');
            Route::get('/template/preview/{id}', [\App\Http\Controllers\Tenant\CosecTemplateController::class, 'preview'])->name('admin.cosec.template.preview');
            Route::get('/signatures/{companyId}', AdminCosecSignature::class)->name('admin.cosec.signature')->middleware('user.type:admin,subscriber');
            Route::get('/credits', AdminCosecCredit::class)->name('admin.cosec.credits')->middleware('user.type:admin,subscriber');
            Route::get('/credit-history/{userId}', AdminCosecCreditHistory::class)->name('admin.cosec.credit-history')->middleware('user.type:admin,subscriber');
        });

        // Subscriber COSEC routes (accessible by admin and subscriber)
        Route::prefix('/subscriber/cosec')->middleware(['user.type:admin,subscriber'])->group(function () {
            Route::get('/{id}', PlaceOrderCosec::class)->name('subscriber.cosec.index');
            Route::get('/{id}/cart', CartCosec::class)->name('subscriber.cosec.cart');
            Route::get('/order/{id}', ViewCosec::class)->name('subscriber.cosec.view');
            Route::get('/{id}/order', OrderCosec::class)->name('subscriber.cosec.order');
            Route::get('/print-word/{id}', [\App\Http\Controllers\Tenant\CosecDocumentController::class, 'printWord'])->name('subscriber.cosec.print-word');
            Route::get('/print-pdf/{id}', [\App\Http\Controllers\Tenant\CosecDocumentController::class, 'printPdf'])->name('subscriber.cosec.print-pdf');
        });

        // Legacy /cosec routes (accessible by admin, subscriber, and director)
        Route::prefix('/cosec')->middleware(['user.type:admin,subscriber,director'])->group(function () {
            // Admin route must come BEFORE /{id} to avoid being caught as a company ID
            Route::get('/admin', AdminCosecIndex::class)->name('cosec.admin')->middleware('user.type:admin,subscriber');
            Route::get('/{id}', PlaceOrderCosec::class)->name('cosec.index');
            Route::get('/{id}/cart', CartCosec::class)->name('cosec.cart');
            Route::get('/order/{id}', ViewCosec::class)->name('cosec.view');
            Route::get('/{id}/order', OrderCosec::class)->name('cosec.order');
            Route::get('/print-word/{id}', [\App\Http\Controllers\Tenant\CosecDocumentController::class, 'printWord'])->name('cosec.print-word');
            Route::get('/print-pdf/{id}', [\App\Http\Controllers\Tenant\CosecDocumentController::class, 'printPdf'])->name('cosec.print-pdf');
        });

        // Director COSEC routes (accessible by directors only)
        Route::prefix('/director/cosec')->middleware(['user.type:director'])->group(function () {
            Route::get('/', \App\Livewire\Tenant\Pages\Cosec\DirectorDashboard::class)->name('director.cosec.dashboard');
            Route::get('/place-order', \App\Livewire\Tenant\Pages\Cosec\DirectorPlaceOrder::class)->name('director.cosec.place-order');
            Route::get('/my-orders', \App\Livewire\Tenant\Pages\Cosec\DirectorMyOrders::class)->name('director.cosec.my-orders');
            Route::get('/signatures', \App\Livewire\Tenant\Pages\Cosec\DirectorPendingSignatures::class)->name('director.cosec.signatures');
            Route::get('/sign/{id}', \App\Livewire\Tenant\Pages\Cosec\DirectorSignOrder::class)->name('director.cosec.sign');
            Route::get('/my-signatures', \App\Livewire\Tenant\Pages\Cosec\DirectorMySignatures::class)->name('director.cosec.my-signatures');
            Route::get('/template/preview/{id}', [\App\Http\Controllers\Tenant\CosecTemplateController::class, 'preview'])->name('director.cosec.template.preview');
        });

        // Tax/LHDN Routes
        Route::prefix('/tax')->name('tax.')->group(function () {
            Route::get('/cp204', \App\Livewire\Tenant\Pages\Tax\Cp204Index::class)->name('cp204.index');
            Route::get('/reminders', \App\Livewire\Tenant\Pages\Tax\RemindersIndex::class)->name('reminders.index');
            Route::get('/reminders/logs', \App\Livewire\Tenant\Pages\Tax\ReminderLogs::class)->name('reminders.logs');
            Route::get('/settings', \App\Livewire\Tenant\Pages\Tax\TaxSettings::class)->name('settings.index');
        });

        // E-Confirmation Routes
        Route::prefix('/e-confirmation')->name('econfirmation.')->group(function () {
            Route::get('/', \App\Livewire\Tenant\Pages\EConfirmation\RequestsIndex::class)->name('index');
            Route::get('/create', \App\Livewire\Tenant\Pages\EConfirmation\CreateRequest::class)->name('create');
            Route::get('/request/{id}', \App\Livewire\Tenant\Pages\EConfirmation\ViewRequest::class)->name('view');
            Route::get('/banks', \App\Livewire\Tenant\Pages\EConfirmation\BankRegistry::class)->name('banks');
            Route::get('/sign/{token}', \App\Livewire\Tenant\Pages\EConfirmation\DirectorSigning::class)->name('sign');
        });
    });
});
