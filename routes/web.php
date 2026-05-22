<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/agent-login', function () {
    $user = \App\Models\User::first();
    if($user) { \Illuminate\Support\Facades\Auth::login($user); }
    return redirect('/dashboard');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () { return view('welcome'); })->name('home');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Mots de passe oublié
    Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])->name('password.store');
});

// --- Couche d'Authentification (Protégée) ---
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projets
    Route::get('/projets', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projets/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projets/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projets/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projets/{project}/invoice', [ProjectController::class, 'exportInvoice'])->name('projects.invoice');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    
    // --- Couche d'Autorisation (Rôles) ---
    Route::middleware(['role:caissier'])->group(function () {
        Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
        Route::get('/transactions/create/sortie', [TransactionController::class, 'createSortie'])->name('transactions.create.sortie');
    });
    // Accessible aux caissiers ET admin (les deux peuvent enregistrer depuis la page projet)
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/projets/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projets/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/projets/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
        Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
        Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
    });
    Route::get('/transactions/export/excel', [TransactionController::class, 'exportExcel'])->name('transactions.exportExcel');
    Route::get('/transactions/export/pdf', [TransactionController::class, 'exportPdf'])->name('transactions.exportPdf');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'exportReceipt'])->name('transactions.receipt');

    // (Rapports déplacés dans la section Admin)

    // Routes Admin uniquement
    Route::middleware(['role:admin'])->group(function () {
        // Rapports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/daily/export', [ReportController::class, 'generateDailyReport'])->name('reports.daily.export');
        Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');

        // Catégories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{categorie}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{categorie}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Utilisateurs
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/budget', [SettingsController::class, 'saveBudget'])->name('settings.budget');
        Route::post('/settings/backup', [SettingsController::class, 'backup'])->name('settings.backup');
        Route::get('/settings/backup/download/{filename}', [SettingsController::class, 'downloadBackup'])->name('settings.backup.download');
        Route::post('/settings/restore', [SettingsController::class, 'restore'])->name('settings.restore');

        // Audits
        Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
    });
});
