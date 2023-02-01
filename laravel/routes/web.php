<?php

use Illuminate\Support\Facades\Route;
use App\Http\Actions;

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

Route::redirect('/', '/login');

Route::get('/login', function () {return view('auth.login');})->name('login');
Route::post('/login', Actions\Auth\LoginAction::class)->name('login_action');

Route::post('/logout', Actions\Auth\LogoutAction::class)->name('logout');

Route::get('/register', Actions\Auth\RegisterAction::class)->name('register');
Route::post('/register/store', Actions\Auth\StoreAction::class)->name('register.store');

Route::middleware('auth')->group(function () {
    Route::get('/', Actions\Home\IndexAction::class)->name('home');

    Route::prefix('/folders')->group(function () {
        Route::get('/create', Actions\Folder\CreateAction::class)->name('folders.create');
        Route::post('/store', Actions\Folder\StoreAction::class)->name('folders.store');

        Route::prefix('/{folder}/tasks')->group(function () {
            Route::get('/', Actions\Task\IndexAction::class)->name('tasks.index');
            Route::get('/create', Actions\Task\CreateAction::class)->name('tasks.create');
            Route::get('/{task}/edit', Actions\Task\EditAction::class)->name('tasks.edit');

            Route::post('/store', Actions\Task\StoreAction::class)->name('tasks.store');
            Route::post('/{task}/update', Actions\Task\UpdateAction::class)->name('tasks.update');
        });
    });
});
