<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ForgotPassController extends Controller
{
    public function sendNewPassword(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = User::where('username', $request->username)
            ->where('email', $request->email)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'کاربری با این مشخصات یافت نشد'], 404);
        }

        $newPassword = Str::random(8);

        $user->password = Hash::make($newPassword);
        $user->save();

        Mail::raw("رمز عبور جدید شما: {$newPassword}", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('بازیابی رمز عبور');
        });

        return response()->json(['message' => 'رمز جدید به ایمیل شما ارسال شد']);
    }
}
