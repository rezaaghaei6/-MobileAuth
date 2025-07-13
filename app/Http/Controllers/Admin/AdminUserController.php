<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    public function create()
    {
        return view('admin.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^9\d{9}$/', 'unique:users,phone'],
            'name' => ['required', 'string', 'max:255'],
            // 'password' => ['required', 'string', 'min:6', 'confirmed'], // حذف شد
        ]);

        User::create([
            'phone' => $request->phone,
            'name' => $request->name,
            'password' => null,  // یا خالی بذار
            'role' => 'admin',
        ]);

        return redirect()->route('admin.add')->with('success', 'ادمین جدید با موفقیت اضافه شد.');
    }
}
