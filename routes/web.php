<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemAdminController;
use App\Models\Wholesaler;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\ChatController;
   
    

Route::get('/', function () {
    return view('welcome');
});
Route::get('/about', function(){
    return view('about');
});

 //need to find an appropriate place to put this route. more work should be done here
Route::get('/about', function(){
    return view('about');
});
Route::post('/insertpdf',[Wholesaler::class,'checkpdf']);

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

 /* //original dashboard
Route::get('/dashboard', function (){
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/chat', [ChatController::class, 'index'])->middleware('auth');

Route::middleware(['auth','systemadmin'])->group(function () {
    Route::get('/dashboard/systemadmin/pending-users', [SystemAdminController::class, 'pendingUsers'])->name('dashboard.systemadmin.pending-users');
    Route::get('/dashboard/systemadmin/all-users', [SystemAdminController::class, 'allUsers'])->name('dashboard.systemadmin.all-users');
    Route::get('/dashboard/systemadmin/make-system-administrator', [SystemAdminController::class,'makeSystemAdministrator'])->name('dashboard.systemadmin.make-system-administrator');
});

Route::post('/approve/{id}', [SystemAdminController::class,'approve'])->name('approve');
Route::post('/reject/{id}', [SystemAdminController::class,'reject'])->name('reject');
Route::get('/redirect',[User::class,'redirectToDashboard']);

Route::post('/delete/{id}',[SystemAdminController::class,'delete'])->name('delete');


Route::post('/dashboard.systemadmin.make-systemadmin/{id}',[SystemAdminController::class,'makeSystemAdmin'])->name('dashboard.systemadmin.make-systemadmin');

Route::get('/delivery-information',function(){
    return view('/delivery-information');
});

Route::get('/cart',[CartController::class,'cart'])
    ->name('cart');