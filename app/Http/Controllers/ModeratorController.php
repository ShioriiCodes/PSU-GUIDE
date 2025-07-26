<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Category;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class ModeratorController extends Controller
{
    public function show($id)
    {
        $moderatorRoles = ['admin', 'usg', 'registrar'];

        $moderator = User::whereIn('role', $moderatorRoles)
            ->with('department')
            ->findOrFail($id);

        // Optional extra check for role match
        if (!in_array($moderator->role, $moderatorRoles)) {
            abort(404, 'Moderator not found.');
        }

        return view('moderators.show', compact('moderator'));
    }

    public function export(Request $request, $id)
    {
        $moderator = User::with('department')
            ->where('role', 'moderator')
            ->findOrFail($id);

        $format = $request->input('format');

        if ($format === 'pdf') {
            return Pdf::loadView('exports.moderator_pdf', compact('moderator'))
                ->download('moderator_' . $moderator->id . '.pdf');
        }

        if ($format === 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Name');
            $sheet->setCellValue('C1', 'Email');
            $sheet->setCellValue('D1', 'Department');
            $sheet->setCellValue('E1', 'Role');
            $sheet->setCellValue('F1', 'Status');

            $sheet->setCellValue('A2', $moderator->id);
            $sheet->setCellValue('B2', $moderator->name);
            $sheet->setCellValue('C2', $moderator->email);
            $sheet->setCellValue('D2', $moderator->department->name ?? 'N/A');
            $sheet->setCellValue('E2', ucfirst($moderator->role));
            $sheet->setCellValue('F2', ucfirst($moderator->status));

            $filename = 'moderator_' . $moderator->id . '.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), $filename);
            (new Xlsx($spreadsheet))->save($tempFile);

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        }

        if ($format === 'txt') {
            $content = "Moderator Details:\n";
            $content .= "ID: {$moderator->id}\n";
            $content .= "Name: {$moderator->name}\n";
            $content .= "Email: {$moderator->email}\n";
            $content .= "Department: " . ($moderator->department->name ?? 'N/A') . "\n";
            $content .= "Role: " . ucfirst($moderator->role) . "\n";
            $content .= "Status: " . ucfirst($moderator->status) . "\n";
            $content .= "Created At: {$moderator->created_at}\n";
            $content .= "Updated At: {$moderator->updated_at}\n";

            return response($content)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', "attachment; filename=moderator_{$moderator->id}.txt");
        }

        return back()->with('error', 'Export format not supported.');
    }
    

    public function usgDashboard()
    {
        // ✅ Fetch only USG-allowed categories
        $categories = Category::whereIn('name', [
            'USG Announcements',
            'Student Activities',
            'Workshops & Seminars',
            'Social Gatherings'
        ])->get();

        // ✅ Announcements posted by the current USG user
        $announcements = Announcement::with(['user', 'category'])
            ->whereHas('user', function ($q) {
                $q->whereIn('role', ['admin', 'registrar', 'usg']);
            })
            ->latest()
            ->get();

        // ✅ Basic user stats
        $admins = User::where('role', 'admin')->count();
        $registrars = User::where('role', 'registrar')->count();
        $usgs = User::where('role', 'usg')->count();
        $faculty = User::where('role', 'faculty')->count();
        $students = User::where('role', 'student')->count();

        // ✅ Announcement status stats
        $total = $announcements->count();
        $approved = $announcements->where('status', 'approved')->count();
        $pending = $announcements->where('status', 'pending')->count();
        $rejected = $announcements->where('status', 'rejected')->count();

        // ✅ Render Blade view with all required data
        return view('dashboard.usg', compact(
            'categories',
            'announcements',
            'admins',
            'registrars',
            'usgs',
            'faculty',
            'students',
            'total',
            'approved',
            'pending',
            'rejected'
        ));
    }

}
