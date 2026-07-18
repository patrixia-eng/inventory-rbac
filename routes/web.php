<?php

use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('inventory.index'));

Route::middleware('auth')->group(function () {
    // First line of defense: route middleware backed by the can_print Gate.
    Route::get('/reports/inventory', [ReportController::class, 'index'])
        ->middleware('can:can_print')
        ->name('reports.inventory');

    Route::resource('inventory', InventoryItemController::class)
        ->parameters(['inventory' => 'inventoryItem']);

    // Admin-only: Role and User management, both gated behind can_manage_users.
    Route::middleware('can:can_manage_users')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);

        //Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        //Route::patch('/users/{user}', [UserManagementController::class, 'updateRole'])->name('users.updateRole');
    });
});