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
}
