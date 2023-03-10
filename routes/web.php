<?php

use App\Http\Controllers\FreeBusyController;
use Illuminate\Support\Facades\Route;

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
Route::get('/', [FreeBusyController::class, 'meeting'])->name('meeting');
Route::post('/meeting/request', [FreeBusyController::class, 'requestMeeting'])->name('meeting.request');
Route::get('/meeting/booking/{date}/{participants}/{length}', [FreeBusyController::class, 'bookMeeting'])->name('meeting.book');
