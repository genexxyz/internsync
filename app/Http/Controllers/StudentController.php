<?php

namespace App\Http\Controllers;

use App\Models\AcceptanceLetter;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\EndorsementRequest;
use App\Models\Evaluation;
use App\Models\Journal;
use App\Models\MoaRequest;
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
        ->with([
            'acceptance_letter', 
            'deployment.supervisor', 
            'deployment.company',
            'deployment.evaluation'  // Add this line
        ])
        ->firstOrFail();
        
    $acceptance_letter = AcceptanceLetter::where('student_id', $student->id)->first();
    
    // Get latest MOA request for the current deployment's company
    $moaRequest = null;
    if ($student->deployment && $student->deployment->company_id) {
        $moaRequest = MoaRequest::where('company_id', $student->deployment->company_id)
            
            ->latest()
            ->first();
    }
    
    // Get latest endorsement letter request
    $endorsementRequest = null;
    if ($student->deployment && $student->deployment->company_id) {
        $endorsementRequest = EndorsementRequest::where('company_id', $student->deployment->company_id)
            ->where('requested_by', $student->id)
            ->latest()
            ->first();
    }
    
    return view('student.ojt-document', compact(
        'student', 
        'acceptance_letter', 
        'moaRequest', 
        'endorsementRequest'
    ));
}


public function requestEndorsement(Request $request)
{
    $student = Auth::user()->student;

    // Check for existing request
    $existingRequest = EndorsementRequest::where('company_id', $student->deployment->company_id)
        ->where('requested_by', $student->id)
        ->whereIn('status', ['requested', 'for_pickup'])
        ->first();

    if ($existingRequest) {
        return back()->with('error', 'You already have a pending endorsement letter request.');
    }

    EndorsementRequest::create([
        'company_id' => $student->deployment->company_id,
        'requested_by' => $student->id,
        'status' => 'requested',
        'requested_at' => now(),
    ]);

    return back();
}

public function viewEvaluation(Evaluation $evaluation)
{
    // Security check
    if ($evaluation->deployment->student_id !== Auth::user()->student->id) {
        abort(403);
    }

    $data = [
        'deployment' => $evaluation->deployment,
        'ratings' => [
            'quality_work' => $evaluation->quality_work,
            'completion_time' => $evaluation->completion_time,
            'dependability' => $evaluation->dependability,
            'judgment' => $evaluation->judgment,
            'cooperation' => $evaluation->cooperation,
            'attendance' => $evaluation->attendance,
            'personality' => $evaluation->personality,
            'safety' => $evaluation->safety,
        ],
        'maxRatings' => [
            'quality_work' => 20,
            'completion_time' => 15,
            'dependability' => 15,
            'judgment' => 10,
            'cooperation' => 10,
            'attendance' => 10,
            'personality' => 10,
            'safety' => 10,
        ],
        'recommendation' => $evaluation->recommendation,
        'totalScore' => $evaluation->total_score,
        'criteria' => [
            'quality_work' => 'Quality of Work (thoroughness, accuracy, neatness, effectiveness)',
            'completion_time' => 'Quality of Work (able to complete work in allotted time)',
            'dependability' => 'Dependability, Reliability, and Resourcefulness',
            'judgment' => 'Judgment (sound decisions, ability to evaluate factors)',
            'cooperation' => 'Cooperation (teamwork, working well with others)',
            'attendance' => 'Attendance (punctuality, regularity)',
            'personality' => 'Personality (grooming, disposition)',
            'safety' => 'Safety (awareness of safety practices)',
        ],
    ];

    $pdf = PDF::loadView('pdfs.evaluation-report', $data);
    return $pdf->stream('Evaluation_Report.pdf');
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

    
public function generateDtr(Request $request)
{
    $student = Auth::user()->student;
    
    // Validate month and year
    $request->validate([
        'month' => 'required|integer|between:1,12',
        'year' => 'required|integer'
    ]);

    $startDate = Carbon::create($request->year, $request->month)->startOfMonth();
    $endDate = Carbon::create($request->year, $request->month)->endOfMonth();

    // Get only approved attendances for the month
    $attendances = Attendance::where('student_id', $student->id)
        ->where('is_approved', 1)
        ->whereBetween('date', [$startDate, $endDate])
        ->orderBy('date')
        ->get();

    $dtrData = [];
    $runningTotalMinutes = 0;
    
    // Initialize data for all days
    for ($day = 1; $day <= $endDate->daysInMonth; $day++) {
        $dtrData[$day] = [
            'am_in' => '',
            'am_out' => '',
            'pm_in' => '',
            'pm_out' => '',
            'hours' => '',
            'minutes' => ''
        ];
    }

    // Fill in approved attendance data
    foreach ($attendances as $attendance) {
        $day = (int)$attendance->date->format('d');
        
        // Handle time in/out display
        if ($attendance->time_in) {
            $timeIn = Carbon::parse($attendance->time_in);
            if ($timeIn->format('A') === 'AM') {
                $dtrData[$day]['am_in'] = $timeIn->format('h:i');
            } else {
                $dtrData[$day]['pm_in'] = $timeIn->format('h:i');
            }
        }

        if ($attendance->time_out) {
            $timeOut = Carbon::parse($attendance->time_out);
            if ($timeOut->format('A') === 'AM') {
                $dtrData[$day]['am_out'] = $timeOut->format('h:i');
            } else {
                $dtrData[$day]['pm_out'] = $timeOut->format('h:i');
            }
        }

        // Calculate daily total hours using the same approach as weekly report
        if ($attendance->total_hours) {
            list($hours, $minutes) = array_pad(explode(':', $attendance->total_hours), 2, 0);
            $dailyMinutes = ((int)$hours * 60) + (int)$minutes;
            
            $dtrData[$day]['hours'] = str_pad(floor($dailyMinutes / 60), 2, '0', STR_PAD_LEFT);
            $dtrData[$day]['minutes'] = str_pad($dailyMinutes % 60, 2, '0', STR_PAD_LEFT);
            
            $runningTotalMinutes += $dailyMinutes;
        }
    }

    // Calculate final totals
    $totalHours = floor($runningTotalMinutes / 60);
    $totalMinutes = $runningTotalMinutes % 60;

    $deployment = $student->deployment;
    $company = $deployment ? $deployment->department->company : null;

    $data = [
        'dtrData' => $dtrData,
        'student' => $student,
        'deployment' => $deployment,
        'company' => $company,
        'month' => Carbon::create($request->year, $request->month)->format('F Y'),
        'totalHours' => str_pad($totalHours, 2, '0', STR_PAD_LEFT),
        'totalMinutes' => str_pad($totalMinutes, 2, '0', STR_PAD_LEFT)
    ];

    $pdf = PDF::loadView('pdfs.daily-time-record', $data);
    
    return $pdf->download('DTR_' . Carbon::create($request->year, $request->month)->format('F_Y') . '_' . 
        $student->last_name . '-' . $student->first_name . '.pdf');
}

}
