<?php
// فایل: app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Office;
use App\Models\Lawyer;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * نمایش لیست کاربران
     */
    public function index(Request $request)
    {
        try {
            // پارامترهای فیلتر
            $role = $request->get('role');
            $status = $request->get('status');
            $search = $request->get('search');

            // ایجاد کوئری پایه
            $query = User::with(['role', 'userable']);

            // فیلتر بر اساس نقش
            if ($role && $role !== 'all') {
                $roleModel = Role::where('name', $role)->first();
                if ($roleModel) {
                    $query->where('role_id', $roleModel->id);
                }
            }

            // فیلتر بر اساس وضعیت
            if ($status) {
                $query->where('is_active', $status === 'active');
            }

            // جستجو
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('national_code', 'like', "%{$search}%");
                });
            }

            // دریافت همه کاربران بدون صفحه‌بندی
            $users = $query->orderBy('created_at', 'desc')->get();

            // فرمت کردن داده‌ها
            $formattedUsers = $users->map(function($user) {
                return $this->formatUserData($user);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedUsers
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت لیست کاربران',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ایجاد کاربر جدید
     */
    public function store(Request $request)
    {
        // اعتبارسنجی داده‌ها
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:100',
                'unique:users,username'
            ],
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'national_code' => 'nullable|string|size:10|unique:users',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'max_lawyers' => 'nullable|integer|min:1|max:100',
            'office_id' => 'nullable|exists:offices,id',
        ],[
            'first_name.required' => 'فیلد "نام" الزامی است.',
            'last_name.required' => 'فیلد "نام خانوادگی" الزامی است.',
            'username.required' => 'فیلد "نام کاربری" الزامی است.',
            'username.unique' => 'این نام کاربری قبلاً ثبت شده است.',
            'password.required' => 'فیلد "کلمه عبور" الزامی است.',
            'password.min' => 'کلمه عبور باید حداقل 6 کاراکتر باشد.',
            'national_code.unique' => 'این کد ملی قبلاً ثبت شده است.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در اعتبارسنجی',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // پیدا کردن نقش
            $role = Role::findOrFail($request->role_id);

            // شروع تراکنش دیتابیس
            \DB::beginTransaction();

            // ابتدا مدل مرتبط را ایجاد می‌کنیم
            $userable = null;
            $userableType = null;

            if ($role->name === 'admin') {
                $userable = Admin::create([
                    'employee_id' => 'ADM' . time(), // استفاده از timestamp برای یکتایی
                    'department' => 'مدیریت سیستم'
                ]);
                $userableType = Admin::class;

            } elseif ($role->name === 'office') {
                $userable = Office::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'license_number' => 'OFF' . time(),
                    'max_lawyers' => $request->max_lawyers ?? 5,
                    // user_id بعداً تنظیم می‌شود
                ]);
                $userableType = Office::class;

            } elseif ($role->name === 'vakil') {
                $userable = Lawyer::create([
                    'bar_association_id' => 'LAW' . time(),
                    'specialty' => $request->specialty ?? 'عمومی',
                    'office_id' => $request->office_id,
                ]);
                $userableType = Lawyer::class;
            }

            // حالا کاربر اصلی را ایجاد می‌کنیم
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'national_code' => $request->national_code,
                'phone' => $request->phone,
                'address' => $request->address,
                'role_id' => $request->role_id,
                'is_active' => true,
                'userable_id' => $userable ? $userable->id : null,
                'userable_type' => $userableType,
            ]);

            // اگر نقش office است، user_id را در مدل Office به‌روزرسانی می‌کنیم
            if ($role->name === 'office' && $userable) {
                $userable->update(['user_id' => $user->id]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'کاربر با موفقیت ایجاد شد',
                'data' => $this->formatUserData($user->fresh(['role', 'userable']))
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'خطا در ایجاد کاربر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * نمایش اطلاعات یک کاربر
     */
    public function show($id)
    {
        try {
            $user = User::with(['role', 'userable'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $this->formatUserData($user)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'کاربر مورد نظر یافت نشد',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * به‌روزرسانی کاربر
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // اعتبارسنجی داده‌ها
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'national_code' => [
                'nullable',
                'string',
                'size:10',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'max_lawyers' => 'nullable|integer|min:1|max:100',
        ],
            [
                'first_name.required' => 'فیلد "نام" الزامی است.',
                'last_name.required' => 'فیلد "نام خانوادگی" الزامی است.',
                'username.required' => 'فیلد "نام کاربری" الزامی است.',
                'username.unique' => 'این نام کاربری قبلاً ثبت شده است.',
                'national_code.unique' => 'این کد ملی قبلاً ثبت شده است.',
            ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در اعتبارسنجی',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \DB::beginTransaction();

            // به‌روزرسانی کاربر اصلی
            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'national_code' => $request->national_code,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // به‌روزرسانی مدل مرتبط
            if ($user->isOffice() && $user->userable) {
                $user->userable->update([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'max_lawyers' => $request->max_lawyers,
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'اطلاعات کاربر با موفقیت به‌روزرسانی شد',
                'data' => $this->formatUserData($user->fresh(['role', 'userable']))
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();

            // لاگ خطا برای دیباگ
            \Log::error('Error updating user: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'خطا در به‌روزرسانی کاربر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف کاربر
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // جلوگیری از حذف ادمین
            if ($user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'امکان حذف مدیر سیستم وجود ندارد'
                ], 403);
            }

            \DB::beginTransaction();

            // حذف مدل مرتبط
            if ($user->userable) {
                $user->userable->delete();
            }

            // حذف کاربر
            $user->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'کاربر با موفقیت حذف شد'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'خطا در حذف کاربر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تغییر وضعیت کاربر
     */
    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);

            // جلوگیری از غیرفعال کردن ادمین
            if ($user->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'امکان تغییر وضعیت مدیر سیستم وجود ندارد'
                ], 403);
            }

            $user->update([
                'is_active' => !$user->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'وضعیت کاربر با موفقیت تغییر کرد',
                'data' => [
                    'is_active' => $user->is_active
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در تغییر وضعیت کاربر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * آمار کاربران
     */
    public function stats()
    {
        try {
            $total = User::count();
            $admin = User::whereHas('role', function($q) {
                $q->where('name', 'admin');
            })->count();
            $office = User::whereHas('role', function($q) {
                $q->where('name', 'office');
            })->count();
            $vakil = User::whereHas('role', function($q) {
                $q->where('name', 'vakil');
            })->count();
            $active = User::where('is_active', true)->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'admin' => $admin,
                    'office' => $office,
                    'vakil' => $vakil,
                    'active' => $active,
                    'inactive' => $total - $active
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت آمار کاربران',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * لیست دفاتر برای انتخاب در فرم وکیل
     */
    public function getOffices()
    {
        try {
            $offices = Office::withCount('lawyers')->get()->map(function($office) {
                return [
                    'id' => $office->id,
                    'name' => $office->name,
                    'max_lawyers' => $office->max_lawyers,
                    'current_lawyers' => $office->lawyers_count,
                    'capacity_left' => $office->max_lawyers - $office->lawyers_count
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $offices
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت لیست دفاتر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * فرمت کردن داده‌های کاربر برای فرانت‌اند
     */
    private function formatUserData($user)
    {
        $formatted = [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'name' => $user->first_name . ' ' . $user->last_name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'national_code' => $user->national_code,
            'phone' => $user->phone,
            'address' => $user->address,
            'role' => $user->role->name,
            'role_text' => $this->getRoleText($user->role->name),
            'is_active' => (bool) $user->is_active,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        // اطلاعات اضافی بر اساس نقش
        if ($user->isOffice() && $user->userable) {
            $formatted['office_name'] = $user->userable->name;
            $formatted['max_lawyers'] = $user->userable->max_lawyers;
            $formatted['lawyers_count'] = $user->userable->lawyers->count();
            $formatted['capacity_left'] = $user->userable->max_lawyers - $user->userable->lawyers->count();
        }

        if ($user->isLawyer() && $user->userable) {
            $formatted['bar_association_id'] = $user->userable->bar_association_id;
            $formatted['specialty'] = $user->userable->specialty;
            $formatted['office_id'] = $user->userable->office_id;
            if ($user->userable->office) {
                $formatted['office_name'] = $user->userable->office->name;
            }
        }

        return $formatted;
    }

    /**
     * دریافت متن نقش
     */
    private function getRoleText($role)
    {
        $roles = [
            'admin' => 'مدیر سیستم',
            'office' => 'دفتر حقوقی',
            'vakil' => 'وکیل'
        ];

        return $roles[$role] ?? 'نامشخص';
    }
}
