<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\ChurchController; // 1. Importe o novo controller
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rota pública para login. O middleware 'guest' impede que usuários já logados acessem.
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest:' . config('auth.defaults.guard'))
    ->name('api.login');


// Rotas protegidas que exigem autenticação via Sanctum.
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');

    Route::post('/logout', [LoginController::class, 'destroy'])->name('api.logout');

    // 2. Adicione as rotas para o recurso de igreja
    // Route::apiResource('church', ChurchController::class)->only(['index', 'update']);
    Route::get('/church', [ChurchController::class, 'index'])->name('api.church');
});
