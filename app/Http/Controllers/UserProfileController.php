<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\Department;

class UserProfileController extends Controller
{
    
    public function edit($id)
    {
        $student = User::findOrFail($id);

        // Roles are stored as plain strings in users table
        $roles = ['Student', 'Faculty'];

        // Departments come from departments table
        $departments = Department::all();

        return view('account.edit', compact('student', 'roles', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role'        => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'password'    => 'nullable|min:6|confirmed', // needs password_confirmation in form
        ]);

        $user = User::findOrFail($id);

        // Role comes directly from string
        $user->role = $request->role;

        // Department is an ID
        $user->department_id = $request->department_id;

        // Update password only if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    
}
