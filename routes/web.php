<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

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

    Route::resource('blogs', BlogController::class);
    Route::post('blogs/comments/store',[BlogController::class, 'comment_store'])->name('blogs.comment.store');
    Route::delete('blogs/comments/delete',[BlogController::class, 'comment_store'])->name('blogs.comment.destroy');
});

require __DIR__.'/auth.php';
