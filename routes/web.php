<?php

use App\Http\Controllers\LatestUpdateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;
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
    Route::get('restaurant', [RestaurantController::class, 'index'])->name('restaurant.index');
});

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('latest-updates', LatestUpdateController::class);
    Route::resource('users', UserController::class);
    Route::resource('restaurant', RestaurantController::class)->except('index');
});

require __DIR__.'/auth.php';
