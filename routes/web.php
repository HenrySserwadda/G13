<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
   $greeting='Hello';
    

Route::get('/', function () use ($greeting){
    return view('welcome',compact('greeting'));
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
Route::get('/staff',function(){
    return view('staff');
 });
Route::get('/customerdashboard',function(){
    return view('customerdashboard');
 });
Route::get('/supplierdashboard',function(){
    return view('supplierdashboard');
 });
Route::get('/wholesalerdashboard',function(){
    return view('wholesalerdashboard');
 });
Route::get('/systemadmin',function(){
    $users=User::all()->latest();//this reruns a view for the system admin to see the users by the latest one that has been added but i am going to work on it so that it does eager loading
    return view('systemadmin');
 });
 //routes for added dashboard kinds

 //original dashboard
Route::get('/dashboard', function () use($greeting){
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
use App\Http\Controllers\ChatController;

Route::get('/chat', [ChatController::class, 'index'])->middleware('auth');
