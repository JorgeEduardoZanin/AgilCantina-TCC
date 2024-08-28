<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CantinaController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rota pública para login
Route::post('login', [AuthController::class, 'login'])->name('login');

// Rotas protegidas por autenticação JWT
Route::middleware('jwt.auth')->group(function () {
    Route::post('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Rotas para admin
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
        Route::resource('admin/cantinas', CantinaController::class);
        Route::resource('admin/users', UserController::class);
    });

    // Rotas para donos de cantinas
    Route::middleware('role:cantina_owner')->group(function () {
        Route::get('cantina/dashboard', [CantinaController::class, 'dashboard']);
        // Outras rotas específicas para donos de cantinas...
    });

    // Rotas para usuários
    Route::middleware('role:user')->group(function () {
        Route::get('user/dashboard', [UserController::class, 'dashboard']);
        // Outras rotas específicas para usuários...
    });
});

// Rota para registro de usuário
Route::apiResource('register-user', UserController::class);

// Rotas para recuperação e reset de senha
Route::post('forget-password', [EmailController::class, 'sendPasswordChange'])->middleware('guest')->name('forget_password');
Route::post('reset-password', [EmailController::class, 'resetPassword'])->middleware('guest')->name('password.update');

// Rotas para verificação de e-mail
Route::middleware('signed')->group(function () {
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});
