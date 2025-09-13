<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


//********************************** Clients Route **********************************
Route::post('/store-client',[ClientController::class,'store']);
Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index']);
Route::put('/update-client/{id}', [ClientController::class, 'update']);
Route::delete('/delete-client/{id}', [ClientController::class, 'destroy']);

//********************************** Lawyer Route **********************************
Route::get('/users', [UserController::class, 'index']);
Route::post('/store-user', [UserController::class, 'store']);
Route::get('/show-user/{id}', [UserController::class, 'show']);
Route::put('/update-user/{id}', [UserController::class, 'update']);
Route::delete('/delete-user/{id}', [UserController::class, 'destroy']);

//********************************** Role Route **********************************
Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::put('/roles/{id}', [RoleController::class, 'update']);
Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
