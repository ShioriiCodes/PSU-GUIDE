<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function edit(int $id)
    {
        $student = User::findOrFail($id);

        $roles = ['Student', 'Faculty'];
        $departments = Department::all();

        if (view()->exists('account.edit')) {
            return view('account.edit', compact('student', 'roles', 'departments'));
        }

        return redirect()->back()->with('status', 'Edit view not available.');
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'role' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);

        $user->role = $request->role;
        $user->department_id = $request->department_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }
}




