<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CantinaController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ManagementController;
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
    
    
    Route::prefix('cantinas/{cantina_id}')->group(function () {
        Route::get('products', [ProductController::class, 'index']);
        Route::get('products/{id}', [ProductController::class, 'show']);
    });
    //TIREI ID
    Route::prefix('cantinas/')->group(function () {
        Route::get('orders/{id}', [OrderController::class, 'show']); 
    });

    Route::get('users/{id}', [UserController::class, 'show']);

    Route::get('/mercadopago/success', [PaymentController::class, 'success'])->name('mercadopago.success');
    Route::get('/mercadopago/failure', [PaymentController::class, 'failure'])->name('mercadopago.failure');
    Route::get('/mercadopago/pending', [PaymentController::class, 'pending'])->name('mercadopago.pending');
  
    // Rotas para admin 
    Route::middleware('role:admin')->group(function () {
        Route::patch('/cantinas/{cantina}/approve', [AdminController::class, 'approveCanteen']);
        Route::patch('/cantinas/{cantina}/desapprove', [AdminController::class, 'disapproveCanteen']);
    });

    // Rotas para donos de cantinas
    Route::middleware('role:cantina')->group(function () {
        
        Route::post('/profile/uploadImageProduct/{product_id}', [ProductController::class, 'imageProduct']);
        Route::get('/profile/imageProduct/{product_id}', [ProductController::class, 'showImageProduct']);
        Route::post('/profile/uploadImageCanteen', [CantinaController::class, 'imageCanteen']);

        Route::get('ordersNotCompleteCanteen', action: [OrderController::class, 'indexNotCompleteCanteen']);
        Route::get('ordersCompleteCanteen', action: [OrderController::class, 'indexCompleteCanteen']);
        //TIREI ID
        Route::prefix('cantinas/')->group(function () {
            Route::post('products', [ProductController::class, 'store']);
            Route::patch('products/{id}', [ProductController::class, 'update']);
            Route::delete('products/{id}', [ProductController::class, 'destroy']);  
        });
        Route::post('check_code',[OrderController::class,'checkWithdrawalCode']);
        Route::put('canteens/{id}', [CantinaController::class, 'update']);
        Route::delete('canteens/{id}', [CantinaController::class, 'destroy']);
        
    });
    
    // Rotas para usuários
    Route::middleware('role:user')->group(function () {
        //rotas imagem
        Route::post('/profile/uploadImageUser', [UserController::class, 'image']);
        Route::get('/profile/imageUser', [UserController::class, 'showImage']);
        //rotas orders fechados e abertos
        Route::get('ordersNotCompleteUser', action: [OrderController::class, 'indexNotCompleteUser']);
        Route::get('ordersCompleteUser', action: [OrderController::class, 'indexCompleteUser']);
        Route::prefix('cantinas/{cantina_id}')->group(function () {
            Route::post('orders', [OrderController::class, 'store']);
        });
   
        Route::get('users', [UserController::class, 'index']);
        Route::put('users/{id}', [UserController::class, 'update']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);
    });

    Route::get('canteens/{id}', [CantinaController::class, 'show']);

});
//rota de notificacao para o webhook mandar uma requisicao http post para meu backend e rota de pagamento para dar get no pedido do qual a rota notificacao mandou 
Route::get('/payments/{id}', [PaymentController::class, 'getPayment']);
Route::post('/notifications', [PaymentController::class, 'handle']);


Route::get('canteens', [CantinaController::class, 'index']);
Route::post('canteens', [CantinaController::class, 'store']);

Route::post('users', [UserController::class, 'store']);

//rotas de esqueci a senha 
Route::post('forget-password', [EmailController::class, 'sendPasswordChange'])->middleware('guest')->name('forget_password');
Route::post('reset-password', [EmailController::class, 'resetPassword'])->middleware('guest')->name('password.update');

//rotas para verificacao de email
Route::middleware('signed')->group(function () {
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
});


Route::get('/profile/imageCanteen', [CantinaController::class, 'showImageCanteen']);

