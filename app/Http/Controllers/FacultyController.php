<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FacultyController extends Controller
{
    /**
     * Show a single faculty member.
     */
    public function show(int $id)
    {
        $faculty = User::where('role', 'faculty')->findOrFail($id);

        if (view()->exists('faculty.show')) {
            return view('faculty.show', compact('faculty'));
        }

        return redirect('/')->with('status', 'Faculty view not available.');
    }

    /**
     * Export faculty basic info as a simple CSV.
     */
    public function export(int $id)
    {
        $faculty = User::where('role', 'faculty')->findOrFail($id);

        \App\Helpers\ActivityLogger::log('export_faculty', \App\Models\User::class, $faculty->id);

        $headers = ['Content-Type' => 'text/csv'];
        $filename = 'faculty_' . $faculty->id . '.csv';

        $callback = function () use ($faculty) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Name', 'Email', 'Department', 'Status']);
            fputcsv($output, [
                $faculty->id,
                $faculty->name,
                $faculty->email,
                optional($faculty->department)->name ?? $faculty->department_id,
                $faculty->status ?? 'active',
            ]);
            fclose($output);
        };

        return Response::stream($callback, 200, array_merge($headers, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]));
    }

    /**
     * Delete a faculty account.
     */
    public function destroy(int $id)
    {
        $faculty = User::where('role', 'faculty')->findOrFail($id);
        $faculty->delete();

        \App\Helpers\ActivityLogger::log('delete_faculty', \App\Models\User::class, $id);

        return redirect()->back()->with('success', 'Faculty deleted successfully.');
    }
}


