<?php
// فایل: app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * ثبت‌نام کاربر جدید
     */
    public function register(Request $request)
    {
        // اعتبارسنجی داده‌های ورودی
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'national_code' => 'required|string|unique:users|size:10',
            'role_id' => 'required|exists:roles,id',
            'email' => 'nullable|email|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در اعتبارسنجی',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // ایجاد کاربر جدید
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'national_code' => $request->national_code,
                'email' => $request->email,
                'role_id' => $request->role_id,
                // userable_id و userable_type بعداً پر می‌شوند
            ]);

            // ایجاد توکن دسترسی
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'کاربر با موفقیت ایجاد شد',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در ایجاد کاربر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ورود به سیستم با نام کاربری و رمز عبور
     */
    public function login(Request $request)
    {
        // اعتبارسنجی داده‌های ورودی
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در اعتبارسنجی',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // پیدا کردن کاربر با نام کاربری
            $user = User::where('username', $request->username)->first();

            // بررسی وجود کاربر و صحت رمز عبور
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'نام کاربری یا رمز عبور اشتباه است'
                ], 401);
            }

            // حذف تمام توکن‌های قبلی کاربر (اختیاری)
            // $user->tokens()->delete();

            // ایجاد توکن دسترسی جدید
            $token = $user->createToken('auth_token')->plainTextToken;

            // بارگذاری اطلاعات مرتبط
            $user->load('role', 'userable');

            return response()->json([
                'success' => true,
                'message' => 'ورود موفقیت‌آمیز',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در ورود به سیستم',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * خروج از سیستم
     */
    public function logout(Request $request)
    {
        try {
            // حذف توکن دسترسی فعلی
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'خروج موفقیت‌آمیز'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در خروج از سیستم',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * دریافت اطلاعات کاربر جاری
     */
    public function user(Request $request)
    {
        try {
            // بارگذاری اطلاعات مرتبط
            $user = $request->user()->load('role', 'userable');

            return response()->json([
                'success' => true,
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت اطلاعات کاربر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * بررسی وضعیت احراز هویت
     */
    public function checkAuth(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                $user->load('role', 'userable');
                return response()->json([
                    'success' => true,
                    'authenticated' => true,
                    'user' => $user
                ]);
            }

            return response()->json([
                'success' => true,
                'authenticated' => false
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در بررسی وضعیت احراز هویت',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
