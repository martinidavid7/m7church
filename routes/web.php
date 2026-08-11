<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\DashboardController;
use App\Livewire\PersonFilter;
use App\Http\Controllers\CityController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\VisitorController;
use App\Models\Visitor;
use App\Http\Controllers\MinistryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\PublicController;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Páginas públicas - visíveis sem autenticação
Route::get('/reunioes', [PublicController::class, 'services'])->name('public.services');
Route::get('/ministerios', [PublicController::class, 'ministries'])->name('public.ministries');

Route::middleware(['auth'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });
});


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


// Rotas de Church - Apenas Admin e Pastor Presidente
Route::middleware(['auth', 'role:Admin|Pastor Presidente'])->group(function () {
    Route::prefix('church')->name('church.')->group(function () {
        Route::get('/', [ChurchController::class, 'index'])->name('index');
        Route::get('/create', [ChurchController::class, 'create'])->name('create');
        Route::post('/store', [ChurchController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ChurchController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ChurchController::class, 'update'])->name('update');
        Route::get('/cities-by-uf/{uf_id}', [ChurchController::class, 'getCitiesByUf'])->name('cities-by-uf');
    });
});


// Rotas de Person - Perfil próprio (todos os usuários autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/my-profile', [PersonController::class, 'editMyProfile'])->name('person.my-profile');
    Route::put('/my-profile/update', [PersonController::class, 'updateMyProfile'])->name('person.update-my-profile');
});

// Rotas de Person - Admin, Secretaria e Pastores podem gerenciar outros
Route::middleware(['auth', 'role:Admin|Pastor Presidente|Pastor Auxiliar|Secretaria'])->group(function () {
    Route::prefix('person')->name('person.')->group(function () {
        Route::get('/', [PersonController::class, 'index'])->name('index');
        Route::get('/create', [PersonController::class, 'create'])->name('create');
        Route::post('/store', [PersonController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PersonController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PersonController::class, 'update'])->name('update');
        Route::get('/cities-by-uf/{uf_id}', [PersonController::class, 'getCitiesByUf'])->name('cities-by-uf');
        Route::get('/export', [PersonController::class, 'export'])->name('export');
        Route::get('/print-blank-form', [PersonController::class, 'printBlankForm'])->name('print-blank-form');
        Route::get('/{id}/print', [PersonController::class, 'print'])->name('print');
    });
});

// Rotas de Visitors e Cities - Admin, Secretaria e Pastores
Route::middleware(['auth', 'role:Admin|Pastor Presidente|Pastor Auxiliar|Secretaria'])->group(function () {
    // Rotas personalizadas de visitantes (antes do resource para terem prioridade)
    Route::get('visitors/export', [VisitorController::class, 'export'])->name('visitors.export');
    Route::get('visitors/print-blank-form', [VisitorController::class, 'printBlankForm'])->name('visitors.print-blank-form');

    Route::resource('ministries', MinistryController::class);

    // Rotas CRUD padrão de visitantes
    Route::resource('visitors', VisitorController::class);

    // Rotas CRUD padrão de cidades
    Route::resource('cities', CityController::class);

    //rotas de tipos de servicos
    Route::resource('service_type', ServiceTypeController::class);

    //rotas de servicos (cultos)
    Route::resource('services', ServiceController::class);

});

// Rotas de Impersonate - Apenas Admin
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/impersonate/take/{user}', function ($userId) {
        $user = \App\Models\User::findOrFail($userId);
        auth()->user()->impersonate($user);
        return redirect()->route('dashboard.index');
    })->name('impersonate.take');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/impersonate/leave', function () {
        auth()->user()->leaveImpersonation();
        return redirect()->route('person.index');
    })->name('impersonate.leave');
});





require __DIR__ . '/auth.php';
