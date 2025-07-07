<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function show($id)
        {
            $faculty = User::where('role', 'faculty')->with('department')->findOrFail($id);

            return view('faculty.show', compact('faculty'));
        }


            public function export(Request $request, $id)
        {
            $faculty = User::with('department')->where('role', 'faculty')->findOrFail($id);

            $format = $request->input('format');

            if ($format === 'json') {
                return response()->json($faculty);
            }

            if ($format === 'excel') {
                // Add Laravel Excel logic here
            }

            if ($format === 'pdf') {
                // Add PDF export logic here
            }

            return back()->with('error', 'Export format not supported.');
        }

}
