<?php

use App\Http\Controllers\ReportController;
use App\Livewire\Chat;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
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
use App\Http\Controllers\VendorController;

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

Route::get('/dashboard/wholesaler',function(){
    return view('dashboard.wholesaler');
 })->middleware(['auth', 'verified'])->name('dashboard.wholesaler');

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

    Route::get('/make-system-administrator', [SystemadminController::class, 'makeSystemAdministrator'])->name('make-system-administrator');

    Route::get('/make-staff-member', [SystemadminController::class, 'makeStaffMember'])->name('make-staff-member');

    Route::post('/make-systemadmin/{id}', [SystemadminController::class, 'makeSystemAdmin'])->name('make-systemadmin');

    Route::post('/make-staff/{id}', [SystemadminController::class, 'makeStaff'])->name('make-staff');

    Route::post('/approve-retailers/{id}', [SystemadminController::class, 'approveRetailers'])->name('approveRetailers');

    Route::post('/approve-suppliers/{id}', [SystemadminController::class, 'approveSuppliers'])->name('approve-suppliers');

    Route::post('/reject-retailers/{id}', [SystemadminController::class, 'rejectRetailers'])->name('rejectRetailers');

    Route::post('/reject-suppliers/{id}', [SystemadminController::class, 'rejectSuppliers'])->name('rejectSuppliers');
});

 


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
Route::get('reports/availableProducts',[ReportController::class,'availableProducts'])->name('reports.availableProducts');
Route::get('/reports/inventory',[ReportController::class,'showOnHand'])->name('reports.inventory');
Route::get('reports/rawMaterialsOnHand',[ReportController::class,'onHand'])->name('reports.rawMaterialsOnHand');

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
    
    // New personalized ML routes
    Route::get('/personalized-recommendations', [MLController::class, 'getPersonalizedRecommendations'])->name('ml.personalized-recommendations');
    Route::get('/user-profile', [MLController::class, 'getUserProfile'])->name('ml.user-profile');
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


//to filter users basing on category
Route::get('/filter',[SystemAdminController::class,'filter'])->name('filter');

//Route for application
Route::post('application',[UserController::class,'application'])->name('application');

//Route for inserting pdf
Route::get('/insertpdf',function(){
    return view('insertpdf');
})->name('insertpdf');

// System admin activity log
Route::get('/dashboard/systemadmin/activity-log', [\App\Http\Controllers\SystemadminController::class, 'activityLog'])->name('dashboard.systemadmin.activity-log');


//Java Server Routes

Route::post('/submit-vendor-pdf', [\App\Http\Controllers\VendorController::class, 'validateFile'])->name('vendor.validate');

Route::get('/activate-java-server', function () {
    // Trigger an actual Java endpoint here if needed
    return ['message' => 'Validation result will be emailed shortly.'];
});

// ML product data API for recommendations
Route::get('/api/products-for-ml', [App\Http\Controllers\MLController::class, 'productsForML']);

// Test route for ML implementation
Route::get('/test-ml', function() {
    $service = app('App\Services\MLProductService');
    $products = $service->getMLProducts('female');
    return response()->json(['count' => count($products), 'products' => $products]);
});

// Test route for enhanced ML system
Route::get('/test-enhanced-ml', function() {
    try {
        $userMLService = app('App\Services\UserMLService');
        $user = \App\Models\User::first();
        if ($user) {
            $preferences = $userMLService->analyzeUserPreferences($user);
            $personalized = $userMLService->getPersonalizedRecommendations($user, 3);
            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'preferences' => $preferences,
                'personalized_count' => count($personalized),
                'personalized_sample' => array_slice($personalized, 0, 2)
            ]);
        } else {
            return response()->json(['success' => false, 'error' => 'No users found']);
        }
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
});

Route::get('/middleware-test', function () {
    return 'Test page';
})->middleware(['web']);



