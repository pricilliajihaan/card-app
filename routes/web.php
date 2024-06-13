<?php

use App\Http\Controllers\CardController;
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
Route::get('/', [CardController::class, 'index'])->name('card.index');
// Route::get("/", [CardController::class,"index"])->name('card.index');
Route::get('/card-register', [CardController::class, 'register'])->name('card.register');
Route::post('/card-register', [CardController::class, 'registerProcess'])->name('card.register_process');
Route::get('/card-member', [CardController::class, 'member'])->name('card.member');
Route::post('/card-member', [CardController::class, 'importData'])->name('card.import_data');
Route::post('/card-truncate', [CardController::class, 'memberTruncate'])->name('card.truncate');
Route::post('/card-ecard', [CardController::class, 'getEcard'])->name('card.ecard');
Route::get('/search', [CardController::class, 'search'])->name('card.search');
Route::get('/card/edit/{id}', [CardController::class, 'edit'])->name('card.edit');
Route::put('/card/update/{id}', [CardController::class, 'update'])->name('card.update');
Route::post('/card/delete/{id}', [CardController::class, 'destroy'])->name('card.destroy');
Route::get('/card/download-template', [CardController::class, 'downloadTemplate'])->name('card.download_template');
Route::post('/card-send-email', [CardController::class, 'sendEmail'])->name('card.sendEmail');
Route::get('/send-reminder/{user}', [CardController::class, 'sendReminder'])->name('send.reminder');