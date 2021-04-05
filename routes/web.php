<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

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

Route::get('/getAllTabs', [AppController::class, 'getAllTabs']);
Route::get('/getTabsAndNotes', [AppController::class, 'getTabsAndNotes']);
Route::post('/getNoteByTabId', [AppController::class, 'getNoteByTabId']);
Route::post('/saveTab', [AppController::class, 'saveTab']);
Route::post('/saveNote', [AppController::class, 'saveNote']);
Route::post('/deleteTab', [AppController::class, 'deleteTab']);
Route::post('/setTabOrder', [AppController::class, 'setTabOrder']);

// ROOT
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '[\/\w\.-]*');
