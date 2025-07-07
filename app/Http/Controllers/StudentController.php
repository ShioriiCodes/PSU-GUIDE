<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function show($id)
        {
            $student = User::where('role', 'student')->with('department')->findOrFail($id);
            return view('students.show', compact('student'));
        }

        public function export(Request $request, $id)
        {
            $student = \App\Models\User::with('department')->findOrFail($id);

            switch ($request->input('format')) {
                case 'json':
                    return response()->json($student);

                case 'excel':
                    $filename = 'student_' . $student->id . '.csv';

                    $headers = [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => "attachment; filename=\"$filename\"",
                    ];

                    $callback = function () use ($student) {
                        $handle = fopen('php://output', 'w');
                        fputcsv($handle, ['ID', 'Name', 'Email', 'Student Number', 'Department', 'Role', 'Status']);
                        fputcsv($handle, [
                            $student->id,
                            $student->name,
                            $student->email,
                            $student->student_number,
                            $student->department->name ?? '',
                            $student->role,
                            $student->status,
                        ]);
                        fclose($handle);
                    };

                    return response()->stream($callback, 200, $headers);

                case 'pdf':
                    // Requires dompdf or snappy — optional for now
                    return back()->with('error', 'PDF export not set up yet.');

                default:
                    return back()->with('error', 'Unsupported export format.');
            }
        }

    public function edit($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function toggleStatus($id)
    {
        $student = User::findOrFail($id);

        $student->status = $student->status === 'active' ? 'inactive' : 'active';
        $student->save();

        if (request()->expectsJson()) {
            return response()->json(['status' => $student->status]);
        }

        return back()->with('success', 'Status updated.');
    }

    public function destroy($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $student->delete();

        return back()->with('success', 'Student deleted.');
    }
}

