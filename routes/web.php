<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\MedicineRequestController;
use App\Http\Controllers\User\KitRequestController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Staff\SupplyController;
use App\Http\Controllers\Staff\CategoryController;
use App\Http\Controllers\Staff\TransactionController;
use App\Http\Controllers\Staff\RequestController;
use App\Http\Controllers\Staff\FirstAidKitController;
use App\Http\Controllers\Staff\ReportController as StaffReportController;
use App\Http\Controllers\Executive\DashboardController as ExecutiveDashboard;
use App\Http\Controllers\Executive\ReportController as ExecutiveReportController;
use App\Http\Controllers\Executive\RequestController as ExecutiveRequestController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== โปรไฟล์ (ทุก role) =====
Route::middleware('auth')->name('profile.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password');
});

// ===== ผู้ใช้บริการ (User) =====
Route::prefix('user')->middleware(['auth', 'role:user'])->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');

    // คำร้องขอรับยา
    Route::get('/medicine-requests', [MedicineRequestController::class, 'index'])->name('medicine-requests.index');
    Route::get('/medicine-requests/create', [MedicineRequestController::class, 'create'])->name('medicine-requests.create');
    Route::post('/medicine-requests', [MedicineRequestController::class, 'store'])->name('medicine-requests.store');
    Route::get('/medicine-requests/{medicineRequest}', [MedicineRequestController::class, 'show'])->name('medicine-requests.show');

    // คำร้องยืมกระเป๋าปฐมพยาบาล
    Route::get('/kit-requests', [KitRequestController::class, 'index'])->name('kit-requests.index');
    Route::get('/kit-requests/create', [KitRequestController::class, 'create'])->name('kit-requests.create');
    Route::post('/kit-requests', [KitRequestController::class, 'store'])->name('kit-requests.store');
    Route::get('/kit-requests/{kitRequest}/print', [KitRequestController::class, 'printLetter'])->name('kit-requests.print');
    Route::get('/kit-requests/{kitRequest}/edit', [KitRequestController::class, 'edit'])->name('kit-requests.edit');
    Route::put('/kit-requests/{kitRequest}', [KitRequestController::class, 'update'])->name('kit-requests.update');
    Route::delete('/kit-requests/{kitRequest}', [KitRequestController::class, 'destroy'])->name('kit-requests.destroy');
    Route::post('/kit-requests/{kitRequest}/return', [KitRequestController::class, 'requestReturn'])->name('kit-requests.return');
});

// ===== เจ้าหน้าที่ห้องพยาบาล (Staff) =====
Route::prefix('staff')->middleware(['auth', 'role:staff'])->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboard::class, 'index'])->name('dashboard');

    // จัดการเวชภัณฑ์
    Route::resource('supplies', SupplyController::class);

    // จัดการหมวดหมู่
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // จัดการกระเป๋าปฐมพยาบาล
    Route::resource('kits', FirstAidKitController::class);
    Route::post('/kits/{kit}/items', [FirstAidKitController::class, 'addItem'])->name('kits.items.add');
    Route::put('/kits/{kit}/items/{item}', [FirstAidKitController::class, 'updateItem'])->name('kits.items.update');
    Route::delete('/kits/{kit}/items/{item}', [FirstAidKitController::class, 'removeItem'])->name('kits.items.remove');

    // ธุรกรรมเวชภัณฑ์
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    // จัดการคำร้อง
    Route::get('/requests/medicine', [RequestController::class, 'medicineRequests'])->name('requests.medicine');
    Route::get('/requests/medicine/{medicineRequest}', [RequestController::class, 'showMedicineRequest'])->name('requests.medicine.show');
    Route::post('/requests/medicine/{medicineRequest}/approve', [RequestController::class, 'approveMedicineRequest'])->name('requests.medicine.approve');
    Route::post('/requests/medicine/{medicineRequest}/reject', [RequestController::class, 'rejectMedicineRequest'])->name('requests.medicine.reject');
    Route::post('/requests/medicine/{medicineRequest}/dispense', [RequestController::class, 'dispenseMedicineRequest'])->name('requests.medicine.dispense');

    Route::get('/requests/kit', [RequestController::class, 'kitRequests'])->name('requests.kit');
    Route::get('/requests/kit/{kitRequest}/print', [RequestController::class, 'printKitRequest'])->name('requests.kit.print');
    Route::post('/requests/kit/{kitRequest}/approve', [RequestController::class, 'approveKitRequest'])->name('requests.kit.approve');
    Route::post('/requests/kit/{kitRequest}/reject', [RequestController::class, 'rejectKitRequest'])->name('requests.kit.reject');
    Route::post('/requests/kit/{kitRequest}/confirm-return', [RequestController::class, 'confirmReturnKit'])->name('requests.kit.confirm-return');

    // รายงาน
    Route::get('/reports/stock', [StaffReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/stock/export-xls', [StaffReportController::class, 'stockExportExcel'])->name('reports.stock.export-xls');
    Route::get('/reports/stock/export-pdf', [StaffReportController::class, 'stockExportPdf'])->name('reports.stock.export-pdf');
    Route::get('/reports/stock/export-csv', [StaffReportController::class, 'stockExportCsv'])->name('reports.stock.export-csv');
    Route::get('/reports/dispensing', [StaffReportController::class, 'dispensing'])->name('reports.dispensing');
    Route::get('/reports/expiry', [StaffReportController::class, 'expiry'])->name('reports.expiry');
});

// ===== ผู้บริหาร (Executive) =====
Route::prefix('executive')->middleware(['auth', 'role:executive'])->name('executive.')->group(function () {
    Route::get('/dashboard', [ExecutiveDashboard::class, 'index'])->name('dashboard');
    Route::get('/reports/stock', [ExecutiveReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/dispensing', [ExecutiveReportController::class, 'dispensing'])->name('reports.dispensing');

    Route::get('/requests/medicine', [ExecutiveRequestController::class, 'medicineRequests'])->name('requests.medicine');
    Route::get('/requests/medicine/{medicineRequest}', [ExecutiveRequestController::class, 'showMedicineRequest'])->name('requests.medicine.show');
    Route::post('/requests/medicine/{medicineRequest}/approve', [ExecutiveRequestController::class, 'approveMedicineRequest'])->name('requests.medicine.approve');
    Route::post('/requests/medicine/{medicineRequest}/reject', [ExecutiveRequestController::class, 'rejectMedicineRequest'])->name('requests.medicine.reject');

    Route::get('/requests/kit', [ExecutiveRequestController::class, 'kitRequests'])->name('requests.kit');
    Route::get('/requests/kit/{kitRequest}', [ExecutiveRequestController::class, 'showKitRequest'])->name('requests.kit.show');
    Route::post('/requests/kit/{kitRequest}/approve', [ExecutiveRequestController::class, 'approveKitRequest'])->name('requests.kit.approve');
    Route::post('/requests/kit/{kitRequest}/reject', [ExecutiveRequestController::class, 'rejectKitRequest'])->name('requests.kit.reject');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/users/template', [UserController::class, 'importTemplate'])->name('users.template');
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
});
