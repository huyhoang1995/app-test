<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileCtrl;
use App\Http\Controllers\AccountCtrl;

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

Route::get('/', function () {
    return view('welcome');
});

// account
Route::get('/accounts', [AccountCtrl::class, 'listAccount']);
Route::get('/accounts/{id}', [AccountCtrl::class, 'detailAccount']);
Route::post('/accounts', [AccountCtrl::class, 'createAccount']);
Route::put('/accounts/{id}', [AccountCtrl::class, 'updateAccount']);
Route::delete('/accounts/{id}', [AccountCtrl::class, 'deleteAccount']);

// file
Route::get('/api/showSerialpaso', [FileCtrl::class, 'getContentFile']);
Route::get('/file', [FileCtrl::class, 'compareFolders']);