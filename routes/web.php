<?php

use App\Http\Controllers\BeerController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ReviewController;
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
    return redirect('dashboard');
});

Route::group(['middleware' => 'auth'], function() {
    Route::resources([
        'dashboard' => RecommendationController::class,
        'beers' => BeerController::class,
        'reviews' => ReviewController::class
    ]);
});

require __DIR__.'/auth.php';
