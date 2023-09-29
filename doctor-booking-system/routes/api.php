<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DrAdminController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::middleware('auth:api')->post('/adminLogin', [DrAdminController::class, 'adminLogin']);

Route::post('/adminLogin', [DrAdminController::class, 'adminLogin']);
Route::post('/adminUpdate', [DrAdminController::class, 'adminUpdate']);
Route::post('/adminSignup', [DrAdminController::class, 'adminSignup']);
Route::get('/getAdminDetails', [DrAdminController::class, 'getAdminDetails']);
Route::get('/getAllAdminDetails', [DrAdminController::class, 'getAllAdminDetails']);
Route::delete('/adminDelete', [DrAdminController::class, 'adminDelete']);
