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

Route::get('/', [WelcomeController::class, 'index'])->name('home');

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


Route::middleware(['auth'])->group(function () {
    Route::prefix('church')->name('church.')->group(function () {
        Route::get('/', [ChurchController::class, 'index'])->name('index');
        Route::get('/create', [ChurchController::class, 'create'])->name('create');
        Route::post('/store', [ChurchController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ChurchController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ChurchController::class, 'update'])->name('update');
        Route::get('/cities-by-uf/{uf_id}', [ChurchController::class, 'getCitiesByUf'])->name('cities-by-uf');
    });
});


Route::middleware(['auth'])->group(function () {
    Route::prefix('person')->name('person.')->group(function () {
        Route::get('/', [PersonController::class, 'index'])->name('index');
        Route::get('/create', [PersonController::class, 'create'])->name('create');
        Route::post('/store', [PersonController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PersonController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PersonController::class, 'update'])->name('update');
        Route::get('/cities-by-uf/{uf_id}', [PersonController::class, 'getCitiesByUf'])->name('cities-by-uf');
        Route::get('/export', [PersonController::class, 'export'])->name('export');
        Route::get('/print-blank-form', [PersonController::class, 'printBlankForm'])->name('print-blank-form');
    });

});

Route::middleware(['auth'])->group(function () {
    // Rotas personalizadas de visitantes (antes do resource para terem prioridade)
    Route::get('visitors/export', [VisitorController::class, 'export'])->name('visitors.export');
    Route::get('visitors/print-blank-form', [VisitorController::class, 'printBlankForm'])->name('visitors.print-blank-form');

    // Rotas CRUD padrão de visitantes
    Route::resource('visitors', VisitorController::class);

    // Rotas CRUD padrão de cidades
    Route::resource('cities', CityController::class);
});


require __DIR__ . '/auth.php';
