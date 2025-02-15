<?php

use App\Http\Controllers\PositionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Models\Room;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome', [
        'room_count' => Room::count(),
        'user_count' => User::count()
    ]);
});

Route::get('/home', function () {
    return view('welcome', [
        'room_count' => Room::count(),
        'user_count' => User::count()
    ]);
});

Route::resource('users', UserController::class);

Route::resource('rooms', RoomController::class);

Route::resource('positions', PositionController::class);

Auth::routes();
