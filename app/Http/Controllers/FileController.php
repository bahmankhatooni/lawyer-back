<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    // لیست پرونده‌ها
    public function index()
    {
        $files = File::all(); // یا می‌تونی با relation لاراول بیاوری
        return response()->json($files);
    }

    // ذخیره پرونده جدید
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'lawyer_id' => 'required|exists:users,id',
        ]);

        $file = File::create([
            'title' => $request->title,
            'status' => $request->status,
            'description' => $request->description,
            'client_id' => $request->client_id,
            'lawyer_id' => $request->lawyer_id,
        ]);

        return response()->json(['message' => 'پرونده با موفقیت ثبت شد', 'file' => $file]);
    }

    // ویرایش پرونده
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'lawyer_id' => 'required|exists:users,id',
        ]);

        $file = File::findOrFail($id);
        $file->update([
            'title' => $request->title,
            'status' => $request->status,
            'description' => $request->description,
            'client_id' => $request->client_id,
            'lawyer_id' => $request->lawyer_id,
        ]);

        return response()->json(['message' => 'پرونده با موفقیت بروزرسانی شد', 'file' => $file]);
    }

    // حذف پرونده
    public function destroy($id)
    {
        $file = File::findOrFail($id);
        $file->delete();

        return response()->json(['message' => 'پرونده با موفقیت حذف شد']);
    }
}
