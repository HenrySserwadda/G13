
 
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
use App\Models\Wholesaler;

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
Route::get('/dashboard/staff',function(){
    return view('dashboard.staff');
 })->middleware(['auth', 'verified'])->name('dashboard.staff');

Route::get('/dashboard/customer',function(){
    return view('dashboard/customer');
 })->middleware(['auth', 'verified'])->name('dashboard.customer');

Route::get('/dashboard/supplier',function(){
    return view('dashboard.supplier');
 })->middleware(['auth', 'verified'])->name('dashboard.supplier');

Route::get('/dashboard/wholesaler',function(){
    return view('dashboard.wholesaler');
 })->middleware(['auth', 'verified'])->name('dashboard.wholesaler');

Route::get('/dashboard/systemadmin',function(){
   // $users=User::all()->latest();//this reruns a view for the system admin to see the users by the latest one that has been added but i am going to work on it so that it does eager loading
    return view('dashboard.systemadmin');
 })->middleware(['auth', 'verified'])->name('dashboard.systemadmin');
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


    Route::get('/chat', Chat::class)->name('chat');
});

require __DIR__.'/auth.php';




// Systemadmin dashboard user management routes
Route::middleware(['auth', 'verified'])->prefix('dashboard/systemadmin')->name('dashboard.systemadmin.')->group(function () {
    Route::get('/all-users', [SystemadminController::class, 'allUsers'])->name('all-users');
    Route::get('/pending-users', [SystemadminController::class, 'pendingUsers'])->name('pending-users');
    Route::get('/make-system-administrator', [SystemadminController::class, 'makeSystemAdministrator'])->name('make-system-administrator');
    Route::post('/make-systemadmin/{id}', [SystemadminController::class, 'makeSystemAdmin'])->name('make-systemadmin');
    Route::post('/approve/{id}', [SystemadminController::class, 'approve'])->name('approve');
    Route::post('/reject/{id}', [SystemadminController::class, 'reject'])->name('reject');
});

 


Route::middleware(['auth'])->group(function () {
    Route::resource('raw_materials', RawMaterialController::class);
});

Route::resource('products', ProductController::class)->middleware('auth');
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

Route::get('/reports/inventory',[ReportController::class,''])->name('reports.inventory');

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
});



