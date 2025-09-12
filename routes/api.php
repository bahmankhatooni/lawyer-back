<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/test', function () {
    return response()->json(['msg' => 'API is working']);
});

//********************************** Clients Route **********************************
//ایجاد موکل
Route::post('/store-client',[ClientController::class,'store']);
// نمایش لیست موکلین
Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index']);
// ویرایش موکل
Route::put('/update-client/{id}', [ClientController::class, 'update']);
// حذف موکل
Route::delete('/delete-client/{id}', [ClientController::class, 'destroy']);
