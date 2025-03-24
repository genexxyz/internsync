<?php

namespace App\Http\Controllers;

use App\Models\AcceptanceLetter;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Journal;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Supervisor;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\WeeklyReport;
use Carbon\Carbon;

class StudentController extends Controller
{
    public $company;
    public $supervisor;
    public function index(): View
    {
        $student = Auth::user()->student;
        $deployment = $student->deployment;
    
    
        $totalHours = Attendance::getTotalApprovedHours($student->id);
        // Get weekly reports count and latest reports
    $weeklyReports = Report::where('student_id', $student->id)
    ->orderBy('week_number', 'desc')
    ->take(3)
    ->get();

// Get today's attendance
$todayAttendance = Attendance::where('student_id', $student->id)
    ->where('date', now()->toDateString())
    ->first();

// Get attendance count
$attendanceCount = Attendance::where('student_id', $student->id)
    ->where('is_approved', true)
    ->count();

return view('student.dashboard', compact(
    'deployment',
    'totalHours',
    'weeklyReports',
    'todayAttendance',
    'attendanceCount'
));
    }

    public function journey(): View
{
    $student = Auth::user()->student;
    
    $deployment = $student->deployment()
        ->with(['company', 'supervisor'])
        ->first();
        $totalHours = Attendance::getTotalApprovedHours($student->id);
    return view('student.ojt-journey', [
        'student' => $student,
        'deployment' => $deployment,
        'hours_rendered' => $totalHours
    ]);
}



    public function taskAttendance(): View
    {
        return view('student.task-and-attendance');
    }

    public function ojtDocument(): View
    {
        $student = Student::where('user_id', Auth::user()->id)
            ->with(['acceptance_letter', 'deployment.supervisor'])
            ->firstOrFail();
        $acceptance_letter = AcceptanceLetter::where('student_id', $student->id)->first();
        
        return view('student.ojt-document', compact('student', 'acceptance_letter'));
    }

    public function generateWeeklyReportPdf(Report $report)
    {
        // Check if user owns this report
        if ($report->student_id !== Auth::user()->student->id) {
            abort(403);
        }
        
        // Get journals with tasks and attendance for the week, excluding rejected entries
        $journals = Journal::whereBetween('date', [$report->start_date, $report->end_date])
            ->where('student_id', $report->student_id)
            ->where('is_approved', '!=', 2) // Exclude rejected journals
            ->with([
                'attendance',
                'taskHistories' => function($query) {
                    $query->orderBy('created_at', 'asc'); // Get history in chronological order
                },
                'taskHistories.task' // Include the base task for description and remarks
            ])
            ->orderBy('date')
            ->get();
        
        // Calculate total hours (excluding rejected entries)
        $totalMinutes = 0;
        foreach ($journals as $journal) {
            if ($journal->attendance && $journal->attendance->total_hours) {
                list($hours, $minutes) = array_pad(explode(':', $journal->attendance->total_hours), 2, 0);
                $totalMinutes += ((int)$hours * 60) + (int)$minutes;
            }
        }
        
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        $formattedTotal = sprintf("%d hours and %d minutes", $hours, $minutes);
        
        $student = $report->student;
        $deployment = $student->deployment;
        $company = $deployment ? $deployment->department->company : null;
        
        // Group task histories by journal date
        foreach ($journals as $journal) {
            $journal->taskHistories = $journal->taskHistories->groupBy('task_id')->map(function ($histories) {
                // Get the latest status for each task
                $latestHistory = $histories->last();
                $latestHistory->task->current_status = $latestHistory->status;
                return $latestHistory;
            })->values();
        }
        
        $data = [
            'report' => $report,
            'journals' => $journals,
            'student' => $student,
            'deployment' => $deployment,
            'company' => $company,
            'totalHours' => $formattedTotal,
            'startDate' => Carbon::parse($report->start_date)->format('M d, Y'),
            'endDate' => Carbon::parse($report->end_date)->format('M d, Y')
        ];
        
        $pdf = PDF::loadView('pdfs.weekly-report', $data);
        
        return $pdf->download('Weekly_Report_Week_' . $report->week_number . '_' .  $student->last_name . '-' . $student->first_name . '.pdf');
    }


}
