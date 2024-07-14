<?php

use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

// Protected routes
Route::middleware('auth.employee')->group(function () {
    Route::get('/', [CardController::class, 'index'])->name('card.index');
    Route::get('/card-manage', [CardController::class, 'manage'])->name('card.manage');
    Route::get('/employees/list', [CardController::class, 'list'])->name('employees.list');
    Route::get('/employees/import', [CardController::class, 'import'])->name('employees.import');
    Route::post('/card-member', [CardController::class, 'importData'])->name('card.import_data');
    Route::get('/card-add', [CardController::class, 'add'])->name('card.add');
    Route::post('/card-add', [CardController::class, 'addProcess'])->name('card.add_process');
    Route::post('/card-truncate', [CardController::class, 'memberTruncate'])->name('card.truncate');
    Route::post('/card-ecard', [CardController::class, 'getEcard'])->name('card.ecard');
    Route::get('/card/edit/{id}', [CardController::class, 'edit'])->name('card.edit');
    Route::put('/card/update/{id}', [CardController::class, 'update'])->name('card.update');
    Route::post('/card/delete/{id}', [CardController::class, 'destroy'])->name('card.destroy');
    Route::get('/card/download-template', [CardController::class, 'downloadTemplate'])->name('card.download_template');
    Route::post('/card-send-email', [CardController::class, 'sendEmail'])->name('card.sendEmail');
    Route::get('/send-reminder/{user}', [CardController::class, 'sendReminder'])->name('send.reminder');
    Route::get('/profile', [CardController::class, 'profile'])->name('user.profile');
    Route::post('/profile', [CardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/remove-image', [CardController::class, 'removeProfileImage'])->name('profile.removeImage');
});