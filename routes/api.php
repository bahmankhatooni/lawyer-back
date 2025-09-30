<?php


use App\Http\Controllers\ForgotPassController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

//********************************** Login & Logout Route **********************************
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


//********************************** Clients Route **********************************
Route::post('/store-client',[ClientController::class,'store']);
Route::get('/clients', [ClientController::class, 'index']);
Route::get('/get-clients', [ClientController::class, 'getclients']);
Route::put('/update-client/{id}', [ClientController::class, 'update']);
Route::delete('/delete-client/{id}', [ClientController::class, 'destroy']);

//********************************** Lawyer Route ***********************************
Route::get('/users', [UserController::class, 'index']);
Route::post('/store-user', [UserController::class, 'store']);
Route::get('/show-user/{id}', [UserController::class, 'show']);
Route::put('/update-user/{id}', [UserController::class, 'update']);
Route::delete('/delete-user/{id}', [UserController::class, 'destroy']);
Route::post('/users/{id}/update-profile', [UserController::class, 'updateProfile']);

//********************************** Role Route *************************************
Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::put('/roles/{id}', [RoleController::class, 'update']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

//********************************** File Route *************************************
Route::get('/files', [FileController::class, 'index']);
Route::get('/files/{id}', [FileController::class, 'show']);
Route::post('/files', [FileController::class, 'store']);
Route::put('/files/{id}', [FileController::class, 'update']);
Route::delete('/files/{id}', [FileController::class, 'destroy']);

//********************************** ForgotPassword Route *************************************
Route::post('/forgot-password', [ForgotPassController::class, 'sendNewPassword']);
