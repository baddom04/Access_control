<?php

use App\Http\Controllers\ApiController;
use App\Http\Middleware\ValidateURIParams;
use Illuminate\Support\Facades\Route;

Route::post('register', [ApiController::class, 'register'])->name('api.register');
Route::post('login', [ApiController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(
    function () {
        Route::post('logout', [ApiController::class, 'logout'])->name('api.logout');
        Route::post('user', [ApiController::class, 'user'])->name('api.user');
        Route::post('positions', [ApiController::class, 'store'])->name('api.positions.store');
        Route::put('positions/{id}', [ApiController::class, 'update'])->where('id', '[0-9]+')->name('api.positions.update');
        Route::delete('positions/{id}', [ApiController::class, 'destroy'])->where('id', '[0-9]+')->name('api.positions.destroy');
        Route::post('rooms', [ApiController::class, 'storeRoom'])->name('api.rooms.store');
    }
);

Route::get('positions/{id?}', [ApiController::class, 'getPositions'])->where('id', '[0-9]+')->name('api.positions.getPositions');

Route::get('rooms/{id?}', [ApiController::class, 'getRooms'])->where('id', '[0-9]+')->name('api.positions.getRooms');
Route::get('roomsPaginated/', [ApiController::class, 'getRoomsPaginated'])->name('api.positions.getRoomsPaginated');

// Route::get('uri-params1/{number}/{string}/{optional?}', function ($number, $string, $optional = null) {
//     return response()->json([
//         'number' => $number,
//         'string' => $string,
//         'optional' => $optional
//     ]);
// })->where('number', '[0-9]+')->where('string', '[a-zA-Z]+');

// Route::get('uri-params2/{number}/{string}/{optional?}', function ($number, $string, $optional = null) {
//     return response()->json([
//         'number' => $number,
//         'string' => $string,
//         'optional' => $optional
//     ]);
// })->middleware(ValidateURIParams::class);

// Route::get('uri-params3/{number}/{string}/{optional?}', function ($number, $string, $optional = null) {

//     $errors = [];
//     if (!filter_var($number, FILTER_VALIDATE_INT)) {
//         $errors['number'] = 'A $number-nek pozitív egész számnak kell lennie';
//     }
//     if (!is_string($string)) {
//         $errors['string'] = 'A $string-nek szövegnek kell lennie';
//     }
//     if (!empty($errors)) {
//         return response()->json($errors, 418);
//     }
//     return response()->json([
//         'number' => $number,
//         'string' => $string,
//         'optional' => $optional
//     ], 200);
// })->middleware(ValidateURIParams::class);
