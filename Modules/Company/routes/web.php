<?php

use Illuminate\Support\Facades\Route;
use Modules\Company\Http\Controllers\CompanyController;

// Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/configuracoes/empresas', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/configuracoes/empresas/cadastrar', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/configuracoes/empresas', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/configuracoes/empresas/{id}/editar', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/configuracoes/empresas/{id}', [CompanyController::class, 'update'])->name('companies.update');
// });
