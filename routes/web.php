<?php

use App\Http\Controllers\LatestUpdateController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeeklyMetricController;
use App\Models\LatestUpdate;
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

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $updates = LatestUpdate::latest()->take(5)->get(); // Get the 5 latest updates
        return view('dashboard', compact('updates'));
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/fetch', [PayrollController::class, 'fetchPayroll'])->name('payroll.fetch');
    Route::get('/payroll/export', [PayrollController::class, 'exportPayroll'])->name('payroll.export');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('restaurant', [RestaurantController::class, 'index'])->name('restaurant.index');

    Route::post('/deposits', [RestaurantController::class, 'depositStore']);
    Route::get('/deposits/{restaurant}', [RestaurantController::class, 'deposits'])->name('restaurant.deposit');
    Route::put('/deposits/{id}', [RestaurantController::class, 'depositUpdate'])->name('restaurant.deposit.update');

    Route::post('/maintenances', [RestaurantController::class, 'maintenanceStore'])->name('maintenances');
    Route::get('/maintenances/{restaurant}', [RestaurantController::class, 'maintenances'])->name('restaurant.maintenance');
    Route::put('/maintenances/{id}', [RestaurantController::class, 'maintenanceUpdate'])->name('restaurant.maintenance.update');

    Route::get('/employee/{restaurant}', [RestaurantController::class, 'employee'])->name('restaurant.employees');
    Route::post('/employee', [RestaurantController::class, 'employeeStore'])->name('employees.store');

    Route::get('/metrics/{branch_code}', [WeeklyMetricController::class, 'index'])->name('metrics.index');
    Route::post('/metrics', [WeeklyMetricController::class, 'store'])->name('metrics.store');
    Route::get('/download-metrics', [WeeklyMetricController::class, 'downloadCSV'])->name('metrics.download');
    Route::put('/weekly-metrics/{id}', [WeeklyMetricController::class, 'update']);
    Route::get('/weekly-metrics/{id}/edit', [WeeklyMetricController::class, 'edit']);

    Route::get('/performance/{branch_code}', [PerformanceController::class, 'index'])->name('performance.index');
    Route::post('/performance/store', [PerformanceController::class, 'store'])->name('performance.store');
    Route::put('/performance/update/{id}', [PerformanceController::class, 'update'])->name('performance.update');

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('latest-updates', LatestUpdateController::class);
    Route::resource('users', UserController::class);
    Route::resource('restaurant', RestaurantController::class)->except('index');
});

require __DIR__.'/auth.php';
