<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PreventGuestAccess; // <-- Imported PreventGuestAccess Middleware
use App\Models\User;
use App\Http\Controllers\AIAdvisorController;

// ==========================================
// PUBLIC & GUEST ROUTES (Guarded with prevent-guest)
// ==========================================
Route::middleware([PreventGuestAccess::class])->group(function () {
    Route::get('/', [GuestController::class, 'guesthome'])->name('guesthome');
    Route::get('/loginn', [GuestController::class, 'login'])->name('login');
    Route::get('/registerr', [GuestController::class, 'register'])->name('register');
    Route::post('/reg', [GuestController::class, 'store'])->name('register.submit');
    Route::post('/log', [GuestController::class, 'loginn'])->name('login.submit');

    Route::get('/verify-otp', [GuestController::class, 'showOtpForm'])->name('otp.form');
    Route::post('/verify-otp', [GuestController::class, 'verifyOtp'])->name('verify.otp');

    // Password Reset Routes
    Route::get('/forgot-password', [UserController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [UserController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [UserController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('password.update');
});


// ==========================================
// AUTHENTICATED COMMON & LOGOUT ROUTES
// ==========================================
Route::middleware([PreventBackHistory::class, 'auth'])->group(function () {
    Route::post('/logout', [GuestController::class, 'logout'])->name('logout'); 
});


// ==========================================
// ADMIN PANEL ROUTES (Secured with Auth & Admin Middleware)
// ==========================================
Route::middleware(['auth', AdminMiddleware::class, PreventBackHistory::class])->group(function () {
    Route::get('/adminhome', [AdminController::class, 'adminhome'])->name('adminhome');
    
    // Admin Expense Management
    Route::get('/createExpense', [AdminController::class, 'createExpense'])->name('createExpense');
    Route::post('/storeExpense', [AdminController::class, 'storeExpense'])->name('storeExpense');
    Route::get('/editExpense/{id}', [AdminController::class, 'editExpense'])->name('editExpense');
    Route::put('/updateExpense/{id}', [AdminController::class, 'updateExpense'])->name('updateExpense');
    Route::delete('/deleteExpense/{id}', [AdminController::class, 'deleteExpense'])->name('deleteExpense');

    // Admin Income Management
    Route::get('/createIncome', [AdminController::class, 'createIncome'])->name('createIncome');
    Route::post('/storeIncome', [AdminController::class, 'storeIncome'])->name('storeIncome');
    Route::get('/editIncome/{id}', [AdminController::class, 'editIncome'])->name('editIncome');
    Route::put('/updateIncome/{id}', [AdminController::class, 'updateIncome'])->name('updateIncome');
    Route::delete('/deleteIncome/{id}', [AdminController::class, 'deleteIncome'])->name('deleteIncome');

    // User Management by Admin
    Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');

    // Reports & Exporting (PDF & Excel) (Disabled - No reports needed)
    // Route::get('/admin/reports', [AdminController::class, 'indexReports'])->name('admin.reports');
    // Route::get('/admin/reports/excel', [AdminController::class, 'exportExcel'])->name('admin.reports.excel');
    // Route::get('/admin/reports/pdf', [AdminController::class, 'exportPdf'])->name('admin.reports.pdf');
});


// ==========================================
// REGULAR USER PANEL ROUTES (Secured with 'auth')
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/userhome', [UserController::class, 'userhome'])->name('userhome');

    // User Income
    Route::get('/addincome', [UserController::class, 'createIncome'])->name('addincome');
    Route::post('/income/store', [UserController::class, 'store'])->name('incomes.store');
    Route::get('/incomes', [UserController::class, 'indexIncome'])->name('income.index');
    Route::get('/incomes/export', [UserController::class, 'exportIncome'])->name('income.export');

    // User Expenses
    Route::get('/addexpense', [UserController::class, 'createExpense'])->name('addexpense');
    Route::post('/expense/store', [UserController::class, 'storeExpense'])->name('expenses.store');
    Route::get('/expenses', [UserController::class, 'indexExpense'])->name('expense.index');
    Route::get('/expenses/export', [UserController::class, 'exportExpense'])->name('expense.export');

    // AI Advisor
    Route::get('/ai-advisor', [AIAdvisorController::class, 'index'])->name('user.ai.advisor');
    Route::post('/ai-advisor/ask', [AIAdvisorController::class, 'ask'])->name('user.ai.ask');

    // Budgets
    Route::get('user/budgets/create', [UserController::class, 'createBudget']);
    Route::post('user/budgets/store', [UserController::class, 'storeBudget']);

    // Profile Settings
    Route::get('/user/profile/edit', [UserController::class, 'editProfile'])->name('user.profile.edit');
    Route::post('/user/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');

    // Debts & Loans
    Route::get('/user/debts', [UserController::class, 'indexDebts'])->name('user.debts.index');
    Route::post('/user/debts', [UserController::class, 'storeDebt'])->name('user.debts.store');
    Route::patch('/user/debts/{id}/status', [UserController::class, 'updateDebtStatus'])->name('user.debts.updateStatus');

    // Savings Goals
    Route::get('/user/savings', [UserController::class, 'indexSavings'])->name('user.savings.index');
    Route::post('/user/savings', [UserController::class, 'storeSavings'])->name('user.savings.store');
    Route::patch('/user/savings/{id}/deposit', [UserController::class, 'depositSavings'])->name('user.savings.deposit');
    Route::patch('/user/savings/{id}/deadline', [UserController::class, 'updateDeadline'])->name('user.savings.updatedeadline');
    Route::delete('/user/savings/{id}', [UserController::class, 'destroySavings'])->name('user.savings.destroy');
});