<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [Controllers\ListingController::class, 'index'])->name('listings.index');









Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// Route::get('/test', function () {
//     return "docker works";
// });

// Route::get('/yes', [Controllers\ListingController::class, 'yes'])->name('listings.yes');

Route::get('/{listing:slug}', [Controllers\ListingController::class, 'show'])->name('listings.show');

Route::get('/{listing:slug}/apply', [Controllers\ListingController::class, 'apply'])->name('listings.apply');