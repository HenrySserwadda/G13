<?php

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
   
    

Route::get('/', function () {
    return view('welcome');
});
Route::get('/about', function(){
    return view('about');
});
//need to find an appropriate place to put this route, most probably in the auth thingy, i dont know yet
Route::get('/companyid',function(){
    return view('companyid');
 });
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
    return view('staff');
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
Route::get('/dashboard', function (){
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/chat', \App\Livewire\Chat::class)->name('chat');
});

require __DIR__.'/auth.php';



//Route::middleware(['auth','systemadmin'])->group(function () {
   // Route::get('/dashboard/pending-users', [SystemadminController::class, 'pendingUsers'])->name('dashboard.pending-users');
   // Route::post('/approve', [SystemadminController::class, 'approve'])->name('approve');
    //Route::post('/reject', [SystemadminController::class, 'reject'])->name('reject');
    //Route::get('/redirect', [SystemadminController::class, 'redirectToDashboard']);
//}); // remember to comment out the middleware and see if the thing still works as expected

 


Route::middleware(['auth'])->group(function () {
    Route::resource('raw_materials', RawMaterialController::class);
});

Route::resource('products', ProductController::class)->middleware('auth');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add')->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');



