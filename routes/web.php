<?php

use App\Http\Controllers\ReportController;
use App\Livewire\Chat;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\WorkforceController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\SupplyCenterController;
use App\Http\Controllers\Systemadmin;
use App\Http\Controllers\SystemadminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\WholesalerRetailerInventoryController;
use App\Http\Controllers\MLController;
use App\Http\Controllers\RawMaterialOrderController;
use App\Models\Wholesaler;
use App\Http\Controllers\SupplierController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/about', function(){
    return view('about');
});
//need to find an appropriate place to put this route, most probably in the auth thingy, i dont know yet
 //need to find an appropriate place to put this route
Route::get('/insertpdf',function(){
    /* request()->validate([
        'wholesalerpdf'=>['required'],
    ]); 
    i am trying to insert server side validation so that a pdf must be entered in order
     for the registration of a whole saler to work but the page refuses to load entirely when i do so*/
    return view('insertpdf');
 });
 //new routes i am adding for the different dashboards based on user type
Route::get('/dashboard/staff', [\App\Http\Controllers\StaffController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard.staff');

Route::get('/dashboard/customer',function(){
    return view('dashboard/customer');
 })->middleware(['auth', 'verified'])->name('dashboard.customer');

Route::get('/dashboard/supplier', [\App\Http\Controllers\SupplierController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard.supplier');

Route::get('/dashboard/wholesaler', [\App\Http\Controllers\WholesalerDashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard.wholesaler');

Route::get('/dashboard/systemadmin', [SystemadminController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard.systemadmin');
 Route::resource('inventories', InventoryController::class)->middleware('auth');


 //routes for added dashboard kinds

 //original dashboard
//Route::get('/dashboard', function (){
   // return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/redirect',[UserController::class,'toDashboard'])->middleware('auth','verified')->name('redirect');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/chat', function () {
        return view('chat-page');
    })->middleware(['web', 'auth', 'verified'])->name('chat');
});

require __DIR__.'/auth.php';




// Systemadmin dashboard user management routes
Route::middleware(['auth', 'verified'])->prefix('dashboard/systemadmin')->name('dashboard.systemadmin.')->group(function () {
    Route::get('/all-users', [SystemadminController::class, 'allUsers'])->name('all-users');

    Route::get('/pending-retailers', [SystemadminController::class, 'pendingRetailers'])->name('pending-retailers');

    Route::get('/pending-suppliers', [SystemadminController::class, 'pendingSuppliers'])->name('pending-suppliers');

    Route::get('/pending-wholesalers', [SystemadminController::class, 'pendingWholesalers'])->name('pending-wholesalers');

    Route::get('/make-system-administrator', [SystemadminController::class, 'makeSystemAdministrator'])->name('make-system-administrator');

    Route::get('/make-staff-member', [SystemadminController::class, 'makeStaffMember'])->name('make-staff-member');

    Route::post('/make-systemadmin/{id}', [SystemadminController::class, 'makeSystemAdmin'])->name('make-systemadmin');

    Route::post('/make-staff/{id}', [SystemadminController::class, 'makeStaff'])->name('make-staff');

    Route::post('/handle-admin-action/{id}', [SystemadminController::class, 'handleAdminAction'])
    ->name('handleAdminAction');

});

Route::post('/application',[UserController::class,'application'])->name('application');
Route::get('insertpdf',function(){
    return view('insertpdf');
})->name('insertpdf');
Route::get('filter',[SystemadminController::class,'filter'])->name('filter');


Route::middleware(['auth'])->group(function () {
    Route::resource('raw_materials', RawMaterialController::class);
});

Route::resource('products', ProductController::class)->middleware('auth');
Route::post('/products/{product}/update-quantity', [ProductController::class, 'updateQuantity'])->name('products.update-quantity');
Route::post('/products/{product}/initiate-remake', [ProductController::class, 'initiateRemake'])->name('products.initiate-remake');
Route::post('/products/{product}/update-remake-status', [ProductController::class, 'updateRemakeStatus'])->name('products.update-remake-status');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.place');

// User orders (wholesaler/retailer)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-orders', [\App\Http\Controllers\UserOrderController::class, 'index'])->name('user-orders.index');
    Route::get('/my-orders/{id}', [\App\Http\Controllers\UserOrderController::class, 'show'])->name('user-orders.show');
});

 //Route for deleting a user (used in all-users.blade.php)
Route::delete('/dashboard/systemadmin/delete/{id}', [SystemadminController::class, 'delete'])->name('delete');


//Routes for the reports
Route::get('/reports/products_report',[ReportController::class,'showPdtsByPrice'])->name('reports.products');
Route::get('/reports/pdtsByPrice',[ReportController::class,'productsByPrice'])->name('reports.pdtsByPrice');

Route::get('/reports/inventory',[ReportController::class,'showOnHand'])->name('reports.inventory');
Route::get('/reports/onHand',[ReportController::class,'onHand'])->name('reports.onHand');

Route::get('/noOfOrders',[ReportController::class,'noOfOrders'])->name('noOfOrders');

Route::get('reports/pdtsPerMonth',[ReportController::class,'productsOrderedPerMonth'])->name('reports.pdtsPerMonth');
Route::get('/reports/sales',[ReportController::class,'showPdtsPerMonth'])->name('reports.sales');

Route::middleware(['auth','verified'])->group(function(){
    Route::get('/manage-orders', [OrderManagementController::class, 'index'])->name('orders.manage.index');
    Route::get('/manage-orders/{id}', [OrderManagementController::class, 'show'])->name('orders.manage.show');
    Route::post('/manage-orders/{id}/status',[OrderManagementController::class, 'updateStatus'])->name('orders.manage.updateStatus');
});

Route::middleware(['auth','verified'])->group(function(){
    Route::resource('wholesaler-retailer-inventory',WholesalerRetailerInventoryController::class);
});

//routes the machine learning scripts
Route::prefix('ml')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/test', function() {
        return 'ML route is working!';
    })->name('ml.test');
    Route::get('/sales-analytics', [MLController::class, 'salesAnalytics'])->name('ml.sales-analytics');
    Route::get('/recommendations/{product}', [MLController::class, 'getRecommendations'])->name('ml.recommendations');
    Route::post('/train', [MLController::class, 'trainModels'])->name('ml.train');
    Route::get('/dashboard', [MLController::class, 'dashboard'])->name('ml.dashboard');
    Route::post('/predict-sales', [MLController::class, 'predictSales'])->name('ml.predict-sales');
    // Added missing personalized recommendations route
    Route::get('/personalized-recommendations', [App\Http\Controllers\MLController::class, 'getPersonalizedRecommendations']);
    Route::get('/user-profile', [App\Http\Controllers\MLController::class, 'getUserProfile']);
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('raw-material-orders', RawMaterialOrderController::class);
});

// AJAX
Route::middleware(['auth', 'verified'])->get('/supplier/{supplier}/raw-materials', function($supplierId) {
    $materials = \App\Models\RawMaterial::where('user_id', $supplierId)
        ->get(['id', 'name', 'quantity', 'unit_price', 'user_id']);
    return response()->json($materials);
});

Route::post('/ml/custom-chart', [App\Http\Controllers\MLController::class, 'customChart'])->middleware(['auth', 'verified']);

// System admin activity log
Route::get('/dashboard/systemadmin/activity-log', [SystemadminController::class, 'activityLog'])->name('dashboard.systemadmin.activity-log');


//Java Server Routes
Route::post('/validate-file', [VendorController::class, 'validateFile'])->name('validate-file');

//Workforce management routes

Route::get('/api/sales-by-month', function() {
    $sales = \App\Models\Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as total')
        ->where('status', 'completed')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');
    return response()->json($sales);
});

Route::get('/middleware-test', function () {
    return 'Test page';
})->middleware(['web']);



Route::get('/workforce', [WorkforceController::class, 'index'])->name('workforce.index');
Route::post('/workforce/allocate', [WorkforceController::class, 'allocateWorkers'])->name('workforce.allocate');
Route::get('/workforce/export/pdf', [WorkforceController::class, 'exportPdfReport'])->name('workforce.exportPdf');
Route::get('/workforce/export/excel', [WorkforceController::class, 'exportExcelReport'])->name('workforce.exportExcel');
Route::resource('supply-centers', SupplyCenterController::class);
Route::resource('workers', WorkerController::class);
Route::get('/workforce/manage', [WorkforceController::class, 'manage'])->name('workforce.manage');
Route::get('/workforce/export', [WorkforceController::class, 'exportExcelReport'])->name('workforce.export');
Route::post('/workforce/refresh', [WorkforceController::class, 'refresh'])->name('workforce.refresh');
Route::resource('workers', WorkerController::class);
Route::get('/workers', [WorkerController::class, 'index'])->name('manage');
Route::resource('workers', WorkerController::class)->except(['index']);
Route::get('/supply-centers', [SupplyCenterController::class, 'index'])->name('manage');
// API endpoint for ML product suggestions (for tag bar and recommendations)
Route::get('/api/products-for-ml', [App\Http\Controllers\MLController::class, 'productsForML']);
Route::get('/workforce/manage', [SupplyCenterController::class, 'index'])->name('workforce.manage');
Route::post('/workers/allocate', [WorkforceController::class, 'allocateWorkers'])->name('workers.allocate');