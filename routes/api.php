<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NfController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return "Enjoy the Silence...";
});

Route::prefix('v1/nf')->middleware("token")->group(function () {
    Route::get('/',                       [NfController::class, 'index'])->name('receiveNfes');
    Route::get('/chave/{chave}',          [NfController::class, 'show'])->name('showNfe');
});
