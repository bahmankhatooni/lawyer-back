<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // ذخیره یک موکل جدید
    public function store(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'national_code' => 'nullable|digits:10',
            'phone' => 'nullable|regex:/^09[0-9]{9}$/',
            'email' => 'nullable|email',
            'address' => 'nullable|string|max:255',
        ]);

        $client = Client::create($request->all());

        return response()->json([
            'message' => 'موکل با موفقیت ثبت شد',
            'client' => $client
        ]);
    }

    // گرفتن همه موکل‌ها
    public function index()
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    // گرفتن اطلاعات یک موکل برای ویرایش
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json($client);
    }

    // بروزرسانی اطلاعات موکل
    public function update(Request $request, $id)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'national_code' => 'nullable|digits:10',
            'phone' => 'nullable|regex:/^09[0-9]{9}$/',
            'email' => 'nullable|email',
            'address' => 'nullable|string|max:255',
        ]);

        $client = Client::findOrFail($id);
        $client->update($request->all());

        return response()->json([
            'message' => 'اطلاعات موکل با موفقیت بروزرسانی شد',
            'client' => $client
        ]);
    }

    // حذف یک موکل
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json([
            'message' => 'موکل با موفقیت حذف شد'
        ]);
    }
}
