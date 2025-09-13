<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);


//********************************** Clients Route **********************************
//ایجاد موکل
Route::post('/store-client',[ClientController::class,'store']);
// نمایش لیست موکلین
Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index']);
// ویرایش موکل
Route::put('/update-client/{id}', [ClientController::class, 'update']);
// حذف موکل
Route::delete('/delete-client/{id}', [ClientController::class, 'destroy']);


//********************************** Lawyer Route **********************************

Route::get('/users', [UserController::class, 'index']);
Route::post('/store-user', [UserController::class, 'store']);
Route::get('/show-user/{id}', [UserController::class, 'show']);
Route::put('/update-user/{id}', [UserController::class, 'update']);
Route::delete('/delete-user/{id}', [UserController::class, 'destroy']);
