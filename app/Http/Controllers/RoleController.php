<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index() {
        return Role::all();
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->name]);
        return response()->json(['message' => 'نقش ایجاد شد', 'role' => $role]);
    }

    public function show($id) {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id) {
        $role = Role::findOrFail($id);
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);
        return response()->json(['message' => 'نقش بروزرسانی شد', 'role' => $role]);
    }

    public function destroy($id) {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json(['message' => 'نقش حذف شد']);
    }
}
