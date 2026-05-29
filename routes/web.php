<?php

use App\Http\Controllers\Account\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\SocialiteLoginController;
use App\Http\Controllers\ExpenseCategoriesController;
use App\Http\Controllers\IncomeCategoriesController;
use App\Http\Controllers\MonthlyEntriesController;
use App\Http\Controllers\MonthlyPeriodsController;
use App\Http\Controllers\Logs\AuditLogsController;
use App\Http\Controllers\Logs\SystemLogsController;
use App\Http\Controllers\MonthlyForecastsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('index');
});

Route::middleware('auth')->get('index', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Account pages
    Route::prefix('account')->group(function () {
        Route::get('overview', [PagesController::class, 'index'])->name('account.overview');
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::put('settings/email', [SettingsController::class, 'changeEmail'])->name('settings.changeEmail');
        Route::put('settings/password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    });

    // Cadastros
    Route::resource('expense-categories', ExpenseCategoriesController::class)
        ->except(['show'])
        ->parameters(['expense-categories' => 'expense_category']);

    Route::resource('income-categories', IncomeCategoriesController::class)
        ->except(['show'])
        ->parameters(['income-categories' => 'income_category']);

    Route::resource('monthly-periods', MonthlyPeriodsController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    Route::patch('monthly-periods/{monthly_period}/close', [MonthlyPeriodsController::class, 'close'])
        ->name('monthly-periods.close');
    Route::patch('monthly-periods/{monthly_period}/reopen', [MonthlyPeriodsController::class, 'reopen'])
        ->name('monthly-periods.reopen');
    Route::get('monthly-periods/{monthly_period}/export/csv', [MonthlyPeriodsController::class, 'exportCsv'])
        ->name('monthly-periods.export.csv');
    Route::get('monthly-periods/{monthly_period}/export/pdf', [MonthlyPeriodsController::class, 'exportPdf'])
        ->name('monthly-periods.export.pdf');

    Route::post('monthly-periods/{monthly_period}/entries', [MonthlyEntriesController::class, 'store'])
        ->name('monthly-periods.entries.store');
    Route::put('monthly-periods/{monthly_period}/entries/{monthly_entry}', [MonthlyEntriesController::class, 'update'])
        ->name('monthly-periods.entries.update');
    Route::delete('monthly-periods/{monthly_period}/entries/{monthly_entry}', [MonthlyEntriesController::class, 'destroy'])
        ->name('monthly-periods.entries.destroy');

    // Logs pages
    Route::prefix('log')->name('log.')->group(function () {
        Route::resource('system', SystemLogsController::class)->only(['index', 'destroy']);
        Route::resource('audit', AuditLogsController::class)->only(['index', 'destroy']);
    });
});

// Rotas do tema Metronic (demo) — só para itens de menu sem controller Laravel dedicado
$menu = theme()->getMenu();
array_walk($menu, function ($val) {
    if (!isset($val['path']) || $val['path'] === 'index') {
        return;
    }

    $routeName = str_replace('/', '.', $val['path']);
    if (Route::has($routeName) || Route::has($routeName.'.index')) {
        return;
    }

    $route = Route::get($val['path'], [PagesController::class, 'index']);

    $route->middleware('auth');
});

Route::middleware('auth')->get('monthly-forecasts', [MonthlyForecastsController::class, 'index'])
    ->name('monthly-forecasts');
Route::middleware('auth')->put('monthly-forecasts', [MonthlyForecastsController::class, 'upsert'])
    ->name('monthly-forecasts.upsert');
Route::middleware('auth')->get('reports/expenses-vs-forecast', [ReportsController::class, 'expensesVsForecast'])
    ->name('reports.expenses-vs-forecast');

Route::resource('users', UsersController::class);

/**
 * Socialite login using Google service
 * https://laravel.com/docs/8.x/socialite
 */
Route::get('/auth/redirect/{provider}', [SocialiteLoginController::class, 'redirect']);

require __DIR__.'/auth.php';
