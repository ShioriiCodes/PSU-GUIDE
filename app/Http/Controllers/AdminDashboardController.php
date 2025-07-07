<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->with('department')->orderBy('created_at', 'desc')->get();
        $moderators = User::whereIn('role', ['admin', 'registrar', 'usg'])->get();
        $faculty = User::where('role', 'faculty')->with('department')->get();

        return view('dashboard.admin', compact('students', 'moderators', 'faculty'));
    }


}
