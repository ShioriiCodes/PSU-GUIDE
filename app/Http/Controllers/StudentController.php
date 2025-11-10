<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class StudentController extends Controller
{
    /**
     * View a single student.
     */
    public function show(int $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        if (view()->exists('students.show')) {
            return view('students.show', compact('student'));
        }

        return redirect('/')->with('status', 'Student view not available.');
    }

    /**
     * Edit form for a student (fallbacks to show if no edit view).
     */
    public function edit(int $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        if (view()->exists('students.edit')) {
            return view('students.edit', compact('student'));
        }

        return redirect()->route('students.show', $student->id);
    }

    /**
     * Export student basic info as a simple CSV download.
     */
    public function export(int $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);

        $headers = ['Content-Type' => 'text/csv'];
        $filename = 'student_' . $student->id . '.csv';

        $callback = function () use ($student) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Name', 'Email', 'Department', 'Student Number', 'Status']);
            fputcsv($output, [
                $student->id,
                $student->name,
                $student->email,
                optional($student->department)->name ?? $student->department_id,
                $student->student_number,
                $student->status ?? 'active',
            ]);
            fclose($output);
        };

        return Response::stream($callback, 200, array_merge($headers, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]));
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(int $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $student->status = ($student->status === 'active') ? 'inactive' : 'active';
        $student->save();

        \App\Helpers\ActivityLogger::log('toggle_student_status', \App\Models\User::class, $student->id);

        return back()->with('success', 'Student status updated to ' . $student->status . '.');
    }

    /**
     * Delete a student account.
     */
    public function destroy(int $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $student->delete();

        \App\Helpers\ActivityLogger::log('delete_student', \App\Models\User::class, $id);

        return redirect()->back()->with('success', 'Student deleted successfully.');
    }
}


