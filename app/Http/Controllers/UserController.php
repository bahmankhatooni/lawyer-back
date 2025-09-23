<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // گرفتن همه کاربران
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // ثبت کاربر جدید
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'phone' => 'nullable|regex:/^09[0-9]{9}$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        // هش کردن پسورد
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'message' => 'کاربر با موفقیت ثبت شد',
            'user' => $user
        ]);
    }

    // گرفتن یک کاربر
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // ویرایش کاربر
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'username' => ['required','string','max:50', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|regex:/^09[0-9]{9}$/',
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        // اگر پسورد خالی بود تغییر نده
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'کاربر با موفقیت بروزرسانی شد',
            'user' => $user
        ]);
    }

    // حذف کاربر
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'کاربر با موفقیت حذف شد'
        ]);
    }
// ویرایش پروفایل کاربر
    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|confirmed|min:6',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ایمیل و موبایل
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        // رمز عبور
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // تصویر پروفایل
        if ($request->hasFile('profile_image')) {
            $path = storage_path('app/public/profile_images');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            // حذف عکس قبلی
            $oldImage = $path.'/'.$user->id.'.jpg';
            if (file_exists($oldImage)) {
                unlink($oldImage);
            }

            // ذخیره عکس جدید با نام id کاربر
            $file = $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            $filename = $user->id.'.'.$extension;
            $file->move($path, $filename);

            // ذخیره مسیر در دیتابیس
            $user->profile_image = 'storage/profile_images/'.$filename;
        }

        $user->save();

        return response()->json(['user' => $user]);
    }



}
