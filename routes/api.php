<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/word/{len}/{start}/{end}/{contains}/{excludes}',
    [WordController::class,'url_request']
);
Route::get('/word/help', [WordController::class,'form_request']);
Route::get('/word/explain/{word}', [WordController::class,'show']);
Route::post('/word/update/{word:word}', [WordController::class,'update']);