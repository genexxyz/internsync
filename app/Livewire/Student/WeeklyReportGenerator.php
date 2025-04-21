<?php

namespace App\Livewire\Student;

use App\Models\Attendance;
use App\Models\Journal;
use App\Models\Notification;
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
    public $deployment;
    public $showWeekDetails = false;
    public $weeklyJournals = [];
    public $weeklyTotal = 0;
    public $reportExists = false;
    public $selectedReport = null;
    public $currentReport = null;
    public $latestReport;
    public $minStartDate;
    public $maxEndDate;

    
protected $rules = [
    'startDate' => [
        'required',
        'date',
        'after_or_equal:minStartDate',
        'before_or_equal:today',
    ],
    'endDate' => [
        'required',
        'date',
        'after_or_equal:startDate',
        'before_or_equal:maxEndDate',
        'before_or_equal:today',
    ],
    'learningOutcomes' => 'required|min:50'
];

protected $messages = [
    'startDate.after_or_equal' => 'Start date must be after the last submitted report',
    'startDate.before_or_equal' => 'Start date cannot be in the future',
    'endDate.after_or_equal' => 'End date must be after or equal to start date',
    'endDate.before_or_equal' => 'End date cannot be more than 7 days from start date or in the future',
    'learningOutcomes.min' => 'Learning outcomes must be at least 50 characters'
];


    public function mount()
    {
        $this->deployment = Auth::user()->student->deployment;
        // Check if deployment exists and has started
        if (!$this->deployment || !$this->deployment->starting_date || $this->deployment->starting_date->gt(Carbon::today())) {
            $this->addError('deployment', 'You cannot generate reports until your deployment has started.');
            return;
        }

        // Check if there are any journals and attendances
        $hasJournalsAndAttendance = Journal::where('student_id', Auth::user()->student->id)
            ->whereHas('attendance')
            ->exists();

        if (!$hasJournalsAndAttendance) {
            $this->addError('journal', 'You need to have at least one attendance and journal entry to generate reports.');
            return;
        }

        $this->latestReport = Report::where('student_id', Auth::user()->student->id)
            ->orderBy('end_date', 'desc')
            ->first();

        $today = Carbon::today();

        if ($this->latestReport) {
            $lastReportEnd = Carbon::parse($this->latestReport->end_date);

            if ($today->gt($lastReportEnd)) {
                // New report period - start from day after last report
                $this->minStartDate = $lastReportEnd->addDay()->format('Y-m-d');
                $this->startDate = null; // Initialize as null
                $this->endDate = null; // Initialize as null
                $this->weekNumber = $this->latestReport->week_number + 1;
            } else {
                // Show existing report
                $this->currentReport = $this->latestReport;
                $this->startDate = $this->latestReport->start_date;
                $this->endDate = $this->latestReport->end_date;
                $this->weekNumber = $this->latestReport->week_number;
                $this->reportExists = true;
            }
        } else {
            // First report - start from deployment start date
            $this->minStartDate = $this->deployment->starting_date;
            $this->startDate = null; // Initialize as null
            $this->endDate = null; // Initialize as null
            $this->weekNumber = 1;
        }
    }

    public function updatedStartDate($value)
    {
        if (!$value) {
            $this->endDate = null;
            $this->maxEndDate = null;
            return;
        }

        try {
            $startDate = Carbon::parse($value);
            $minDate = Carbon::parse($this->minStartDate);

            if ($startDate->lt($minDate)) {
                $this->addError('startDate', 'Start date must be after the last submitted report');
                $this->startDate = null;
                return;
            }

            if ($startDate->gt(Carbon::today())) {
                $this->addError('startDate', 'Start date cannot be in the future');
                $this->startDate = null;
                return;
            }

            $this->startDate = $value;
            $this->updateMaxEndDate();
            $this->loadWeeklyData();
        } catch (\Exception $e) {
            $this->addError('startDate', 'Invalid date format');
            $this->startDate = null;
        }
    }

    protected function updateMaxEndDate()
    {
        if (!$this->startDate) {
            $this->maxEndDate = null;
            $this->endDate = null;
            return;
        }

        $startDate = Carbon::parse($this->startDate);
        $proposedEndDate = $startDate->copy()->addDays(6);
        $today = Carbon::today();

        // Set end date as the earlier of proposed end date or today
        if ($proposedEndDate->gt($today)) {
            $this->maxEndDate = $today->format('Y-m-d');
            $this->endDate = null; // Don't set end date automatically
        } else {
            $this->maxEndDate = $proposedEndDate->format('Y-m-d');
            $this->endDate = null; // Don't set end date automatically
        }
    }

    public function updatedEndDate($value)
    {
        if (!$value) return;

        try {
            $endDate = Carbon::parse($value);
            $startDate = Carbon::parse($this->startDate);

            if ($endDate->lt($startDate)) {
                $this->addError('endDate', 'End date must be after start date');
                return;
            }

            if ($endDate->diffInDays($startDate) > 6) {
                $this->addError('endDate', 'Maximum range is 7 days');
                return;
            }

            if ($endDate->gt(Carbon::today())) {
                $this->addError('endDate', 'End date cannot be in the future');
                return;
            }

            $this->endDate = $value;
            $this->loadWeeklyData();
        } catch (\Exception $e) {
            $this->addError('endDate', 'Invalid date format');
        }
    }

    public function loadWeeklyData()
    {
        // Get all dates in range
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        // Get journals with tasks and attendance
        $journals = Journal::with(['attendance', 'taskHistories' => function ($query) {
            $query->orderBy('changed_at', 'desc');
        }, 'taskHistories.task'])
            ->where('student_id', Auth::user()->student->id)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->get()
            ->keyBy(fn($journal) => $journal->date->format('Y-m-d'));

        // Calculate total hours
        $this->weeklyTotal = $journals->sum(function ($journal) {
            if (!$journal->attendance?->total_hours) return 0;
            list($h, $m) = explode(':', $journal->attendance->total_hours);
            return ($h * 60) + $m;
        });

        // Create entries for all days in range
        $this->weeklyJournals = collect();
        $current = $start->copy();
        while ($current <= $end) {
            $dateString = $current->format('Y-m-d');
            $this->weeklyJournals[$dateString] = $journals->get($dateString);
            $current->addDay();
        }
    }

    public function viewPastReport($reportId)
    {
        $report = Report::findOrFail($reportId);

        // Update component properties to show selected report
        $this->selectedReport = $report;
        $this->startDate = $report->start_date;
        $this->endDate = $report->end_date;
        $this->weekNumber = $report->week_number;
        $this->learningOutcomes = $report->learning_outcomes;

        // Load journals for the selected report's date range
        $journals = Journal::with(['attendance', 'taskHistories' => function ($query) {
            $query->orderBy('changed_at', 'desc');
        }, 'taskHistories.task'])
            ->where('student_id', Auth::user()->student->id)
            ->whereBetween('date', [$report->start_date, $report->end_date])
            ->get()
            ->keyBy(fn($journal) => $journal->date->format('Y-m-d'));

        // Calculate total hours
        $this->weeklyTotal = $journals->sum(function ($journal) {
            if (!$journal->attendance?->total_hours) return 0;
            list($h, $m) = explode(':', $journal->attendance->total_hours);
            return ($h * 60) + $m;
        });

        // Create entries for all days in range
        $this->weeklyJournals = collect();
        $current = Carbon::parse($report->start_date);
        $end = Carbon::parse($report->end_date);

        while ($current <= $end) {
            $dateString = $current->format('Y-m-d');
            $this->weeklyJournals[$dateString] = $journals->get($dateString);
            $current->addDay();
        }

        $this->showWeekDetails = true;
    }

    public function viewWeekDetails()
    {
        $this->loadWeeklyData();
        $this->showWeekDetails = true;
    }

    public function generatePdf()
    {
        $report = $this->selectedReport ?? $this->currentReport;

        if (!$report) {
            $this->dispatch('alert', type: 'error', text: 'No report found');
            return;
        }
        $this->showWeekDetails = false;
        return redirect()->route('student.weekly-report.pdf', ['report' => $report->id]);
    }

    public function submit()
    {
        if ($this->reportExists) return;

        $this->validate();

        Report::create([
            'student_id' => Auth::user()->student->id,
            'week_number' => $this->weekNumber,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'learning_outcomes' => $this->learningOutcomes,
            'submitted_at' => now(),
        ]);
        $fullname = Auth::user()->student->name();
        $start = Carbon::parse($this->startDate)->format('F j, Y');
        $end = Carbon::parse($this->endDate)->format('F j, Y');
        Notification::send(
            $this->deployment->supervisor->user_id,
            'weekly_report_submitted',
            'Weekly Report Submitted',
            "Week {$this->weekNumber} ({$start}-{$end}) of {$fullname} has been submitted for review.",
            'supervisor.weeklyReports',
            'fa-calendar-week'
        );
        $this->dispatch('alert', type: 'success', text: 'Weekly report submitted successfully.');
        session()->flash('message', 'Weekly report submitted successfully.');
        $this->checkReportExists();
        $this->render();
    }

    protected function checkReportExists()
    {
        $this->reportExists = Report::where('student_id', Auth::user()->student->id)
            ->where('week_number', $this->weekNumber)
            ->exists();
    }

    public function closeWeekDetails()
    {
        $this->showWeekDetails = false;
        $this->reset(['startDate', 'endDate', 'learningOutcomes']);
        $this->weeklyJournals = [];
        $this->weeklyTotal = 0;
        $this->reportExists = false;
        $this->currentReport = null;
        $this->selectedReport = null;
        $this->weekNumber = $this->latestReport ? $this->latestReport->week_number + 1 : 1;
    }

    public function render()
    {
        return view('livewire.student.weekly-report-generator', [
            'previousReports' => Report::where('student_id', Auth::user()->student->id)
                ->orderBy('week_number', 'desc')
                ->get()
        ]);
    }
}
