<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CantinaController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
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
       
    });

    // Rotas para donos de cantinas
    Route::middleware('role:cantina')->group(function () {
        Route::prefix('cantinas/{cantina_id}')->group(function () {
            Route::get('products', [ProductController::class, 'index']);
            Route::post('products', [ProductController::class, 'store']);
            Route::put('products/{id}', [ProductController::class, 'update']);
            Route::delete('products/{id}', [ProductController::class, 'destroy']);
        });
       
    });
    Route::post('check_code',[OrderController::class,'checkWithdrawalCode']);
    // Rotas para usuários
    Route::middleware('role:user')->group(function () {
        Route::prefix('cantinas/{cantina_id}')->group(function () {
            Route::get('orders', [OrderController::class, 'index']);
            Route::post('orders', [OrderController::class, 'store']);
            Route::put('orders/{id}', [OrderController::class, 'update']);
            Route::delete('orders/{id}', [OrderController::class, 'destroy']);

Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');
Route::get('/payment/pending', [PaymentController::class, 'pending'])->name('payment.pending');
        });
    });
});

 

Route::apiResource('register-cantinas', CantinaController::class);

Route::apiResource('register-products', ProductController::class);

Route::apiResource('register-user', UserController::class);

Route::post('forget-password', [EmailController::class, 'sendPasswordChange'])->middleware('guest')->name('forget_password');
Route::post('reset-password', [EmailController::class, 'resetPassword'])->middleware('guest')->name('password.update');


Route::middleware('signed')->group(function () {
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});
