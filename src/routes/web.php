<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ▼ Fortify の login/register はここで自動登録される
//   ※ ここには何も書かない

// ▼ 認証不要ルート（トップページはログイン画面へリダイレクト）
Route::get('/', function () {
    return redirect('/login');
});

// ▼ 認証が必要なルート
Route::middleware('auth')->group(function () {

    Route::get('/home', [TodoController::class, 'index'])->name('home');

    Route::get('/todos', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::patch('/todos/update', [TodoController::class, 'update']);
    Route::delete('/todos/delete', [TodoController::class, 'destroy']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::patch('/categories/update', [CategoryController::class, 'update']);
    Route::delete('/categories/delete', [CategoryController::class, 'destroy']);

    Route::get('/todos/search', [TodoController::class, 'search']);
});

// ▼ ログアウト
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->middleware('auth');
