<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DrAdminController;
use App\Http\Controllers\UserController;

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


// Doctor Routes
Route::post('/adminLogin', [DrAdminController::class, 'adminLogin']);
Route::post('/adminUpdate', [DrAdminController::class, 'adminUpdate']);
Route::post('/adminSignup', [DrAdminController::class, 'adminSignup']);
Route::get('/getAdminDetails', [DrAdminController::class, 'getAdminDetails']);
Route::get('/getAllAdminDetails', [DrAdminController::class, 'getAllAdminDetails']);
Route::delete('/adminDelete', [DrAdminController::class, 'adminDelete']);


// User Routes
Route::post('/addUser', [UserController::class, 'addUser']);
Route::get('/getAllUser', [UserController::class, 'getAllUser']);
Route::get('/getUserid', [UserController::class, 'getUserid']);
Route::post('/updateUser', [UserController::class, 'updateUser']);
Route::delete('/userDelete', [UserController::class, 'userDelete']);
Route::post('/userLogin', [UserController::class, 'userLogin']);
