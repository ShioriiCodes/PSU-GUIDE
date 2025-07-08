<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;
use App\Models\Category;
class AdminDashboardController extends Controller
{
    public function index()
    {$categories = Category::all();
        $students = User::where('role', 'student')->with('department')->orderBy('created_at', 'desc')->get();
        $moderators = User::whereIn('role', ['admin', 'registrar', 'usg'])->get();
        $faculty = User::where('role', 'faculty')->with('department')->get();

        return view('dashboard.admin', compact('students','categories', 'moderators', 'faculty'));
    }

        public function store(Request $request)
        {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'is_approved' => 'nullable|boolean',
            ]);

            Announcement::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'category_id' => $validated['category_id'],
                'is_approved' => $request->has('is_approved'),
                'posted_by' => Auth::id(), // track who posted
                ]);

            return redirect()->back()->with('success', 'Announcement posted successfully.');
        }

}
