<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'auth'], function(){

    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('blogs', BlogController::class)->middleware('post_owner');
    Route::post('blogs/comments/store',[BlogController::class, 'comment_store'])->name('blogs.comment.store');
    Route::post('blogs/comments/update',[BlogController::class, 'comment_update'])->name('blogs.comment.update');
    Route::delete('blogs/comments/delete',[BlogController::class, 'comment_delete'])->name('blogs.comment.destroy');

    Route::resource('profile', ProfileController::class);
    Route::get('profile/{profile}', [ProfileController::class,'edit'])->name('profile.show');
    // Route::put('profile/{profile}', [ProfileController::class,'update'])->name('profile.update');
});

require __DIR__.'/auth.php';
