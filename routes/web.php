<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/contacts');

Route::prefix('contacts')->name('contacts.')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::match(['get', 'post'], '/new', [ContactController::class, 'create'])->name('create');
    Route::match(['get', 'post'], '/{contact}/edit', [ContactController::class, 'edit'])->name('edit');
    Route::delete('/{contact}', [ContactController::class, 'destroy'])->name('destroy');
});
