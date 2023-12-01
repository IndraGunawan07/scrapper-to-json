<?php

use App\Http\Controllers\ScrapperController;
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
//     redirect('/scrapper');
// });


Route::get('/', [ScrapperController::class, 'index'])->name('index');
Route::delete('/', [ScrapperController::class, 'delete'])->name('delete');
