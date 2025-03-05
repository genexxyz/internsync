<?php

namespace App\Livewire\Student;

use App\Models\Attendance;
use App\Models\Journal;
use App\Models\Report;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
class WeeklyReportGenerator extends Component
{
    public $weekNumber;
    public $startDate;
    public $endDate;
    public $learningOutcomes;
    public $currentWeek;
    public $deployment;
    public $showWeekDetails = false;
    public $weeklyJournals = [];
    public $weeklyTotal = 0;
    public $dailyTotal = 0;
    public $journalCount = 0;
    public $reportExists = false;
    public $workDays = 5; // Default to Mon-Fri, can be 6 for Mon-Sat
    public $selectedReport = null;

    protected $rules = [
        'learningOutcomes' => 'required|min:30',
    ];

// Update the mount() method
public function mount()
{
    $this->deployment = Auth::user()->student->deployment;
    if (!$this->deployment || !$this->deployment->starting_date) {
        return;
    }

    // Get work days from deployment settings if available
    $this->workDays = $this->deployment->work_days ?? 5;

    $startDate = Carbon::parse($this->deployment->starting_date);
    // Fix: Round the week number to whole number
    $this->currentWeek = floor($startDate->diffInDays(now()) / 7) + 1;
    
    $this->weekNumber = $this->currentWeek;
    $weekStart = $startDate->addWeeks($this->currentWeek - 1);
    
    // Ensure week starts on Monday
    while ($weekStart->dayOfWeek !== Carbon::MONDAY) {
        $weekStart->addDay();
    }
    
    $this->startDate = $weekStart->format('Y-m-d');
    // Set end date based on work days (5 or 6)
    $this->endDate = $weekStart->copy()->addDays($this->workDays - 1)->format('Y-m-d');
    
    $this->calculateWeeklyStats();
    $this->checkReportExists();
}

// Update the viewPastReport method
public function viewPastReport($reportId)
{
    $this->selectedReport = Report::with('student')->findOrFail($reportId);
    
    // Set the dates to match this report's time period
    $this->startDate = $this->selectedReport->start_date;
    $this->endDate = $this->selectedReport->end_date;
    $this->weekNumber = $this->selectedReport->week_number;
    
    // Recalculate stats for this specific week
    $this->calculateWeeklyStats();
    
    // Get journals for this past week
    $this->viewWeekDetails();
}
// Add a new method to calculate weekly stats
protected function calculateWeeklyStats()
{
    // Count journals
    $journals = Journal::with('attendance')
        ->where('student_id', Auth::user()->student->id)
        ->whereBetween('date', [$this->startDate, $this->endDate]);
    
    // Apply work days filter
    if ($this->workDays === 5) {
        $journals->whereRaw("DAYOFWEEK(date) NOT IN (1, 7)");
    } else {
        $journals->whereRaw("DAYOFWEEK(date) != 1");
    }
    
    $this->journalCount = $journals->count();
    
    // Calculate total hours for the selected date range
    $attendances = Attendance::where('student_id', Auth::user()->student->id)
        ->whereBetween('date', [$this->startDate, $this->endDate]);
        
    // Apply same work days filter
    if ($this->workDays === 5) {
        $attendances->whereRaw("DAYOFWEEK(date) NOT IN (1, 7)");
    } else {
        $attendances->whereRaw("DAYOFWEEK(date) != 1");
    }
    
    // Sum up the hours
    $totalMinutes = 0;
    $attendances->get()->each(function($attendance) use (&$totalMinutes) {
        if ($attendance->total_hours) {
            list($hours, $minutes) = array_pad(explode(':', $attendance->total_hours), 2, 0);
            $totalMinutes += ($hours * 60) + $minutes;
        }
    });
    
    // Format the result
    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;
    $this->weeklyTotal = sprintf("%02d:%02d", $hours, $minutes);
}
public function viewWeekDetails()
{
    $this->weeklyJournals = collect();
    $currentDate = Carbon::parse($this->startDate);
    $endDate = Carbon::parse($this->endDate);

    // Get all journals for the week with their attendances
    $journals = Journal::with('attendance')
        ->where('student_id', Auth::user()->student->id)
        ->whereBetween('date', [$this->startDate, $this->endDate]);

    // Apply work days filter
    if ($this->workDays === 5) {
        $journals->whereRaw("DAYOFWEEK(date) NOT IN (1, 7)");
    } else {
        $journals->whereRaw("DAYOFWEEK(date) != 1");
    }

    $journalsByDate = $journals->get()->keyBy(function ($journal) {
        return $journal->date->format('Y-m-d');
    });

    // Create entries for each work day
    while ($currentDate <= $endDate) {
        if (($this->workDays === 5 && $currentDate->isWeekday()) || 
            ($this->workDays === 6 && !$currentDate->isSunday())) {
            $dateString = $currentDate->format('Y-m-d');
            $journal = $journalsByDate->get($dateString);
            
            // Calculate daily total if attendance exists
            $dailyTotal = '00:00';
            if ($journal && $journal->attendance) {
                $dailyTotal = $journal->attendance->total_hours ?? '00:00';
            }

            $this->weeklyJournals[$dateString] = [
                'journal' => $journal,
                'daily_total' => $dailyTotal
            ];
        }
        $currentDate->addDay();
    }

    $this->showWeekDetails = true;
}

    
public function hydrate()
{
    // Reset selected report when component re-renders
    if (!$this->showWeekDetails) {
        $this->selectedReport = null;
    }
}




public function formatHoursAndMinutes($timeString)
{
    if (!$timeString || $timeString === '00:00') {
        return '0 hours';
    }

    list($hours, $minutes) = array_pad(explode(':', $timeString), 2, 0);
    
    // Convert to integers and remove leading zeros
    $hours = (int)$hours;
    $minutes = (int)$minutes;
    
    if ($hours === 0) {
        return "{$minutes} minute" . ($minutes !== 1 ? 's' : '');
    }

    if ($minutes === 0) {
        return "{$hours} hour" . ($hours !== 1 ? 's' : '');
    }

    return "{$hours} hour" . ($hours !== 1 ? 's' : '') . " and {$minutes} minute" . ($minutes !== 1 ? 's' : '');
}

    protected function checkReportExists()
    {
        $this->reportExists = Report::where('student_id', Auth::user()->student->id)
            ->where('week_number', $this->weekNumber)
            ->exists();
    }

    public function submit()
{
    // Only validate if report doesn't exist
    if ($this->reportExists) {
        return;
    }

    $this->validate([
        'learningOutcomes' => 'required|min:50'
    ]);

    Report::create([
        'student_id' => Auth::user()->student->id,
        'week_number' => $this->weekNumber,
        'start_date' => $this->startDate,
        'end_date' => $this->endDate,
        'learning_outcomes' => $this->learningOutcomes,
        'submitted_at' => now(),
    ]);

    session()->flash('message', 'Weekly report submitted successfully.');
    $this->checkReportExists();
    $this->render();
}
public function generatePdf()
{
    $reportId = $this->selectedReport ? $this->selectedReport->id : null;
    
    // If no selected report but report exists, find the report for current week
    if (!$reportId && $this->reportExists) {
        $report = Report::where('student_id', Auth::user()->student->id)
            ->where('week_number', $this->weekNumber)
            ->first();
        
        if ($report) {
            $reportId = $report->id;
        }
    }
    
    if (!$reportId) {
        $this->dispatch('alert', ['type' => 'error', 'message' => 'No report available to generate PDF']);
        return;
    }
    
    // Redirect to PDF route with report ID
    return redirect()->route('student.weekly-report.pdf', ['report' => $reportId]);
}
    public function render()
    {
        $previousReports = Report::where('student_id', Auth::user()->student->id)
            ->orderBy('week_number', 'desc')
            ->get();

        return view('livewire.student.weekly-report-generator', [
            'previousReports' => $previousReports
        ]);
    }
}