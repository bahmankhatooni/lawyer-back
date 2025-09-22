<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use function Laravel\Prompts\password;

class AuthController extends Controller
{
    // متد لاگین
    public function login(Request $request)
    {
        // ولیدیشن اولیه
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'نام کاربری را وارد کنید',
            'password.required' => 'کلمه عبور را وارد کنید',
        ]);

        // بررسی یوزر
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'نام کاربری یا کلمه عبور اشتباه است'
            ], 401);
        }

        // تولید توکن ساده (بدون sanctum)
        $token = bin2hex(random_bytes(40));

        return response()->json([
            'message' => 'ورود موفقیت‌آمیز بود',
            'user'    => $user,
            'token'   => $token,
            'avatar' => $user->profile_image,  // این اضافه بشه
        ]);
    }

    // متد لاگ‌اوت
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'با موفقیت خارج شدید'
        ]);
    }
}
