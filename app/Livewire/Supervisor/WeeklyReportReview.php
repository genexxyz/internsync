<?php

namespace App\Livewire\Supervisor;

use App\Models\Report;
use App\Models\Journal;
use App\Models\Attendance;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WeeklyReportReview extends Component
{
    public $reportId;
    public $report;
    public $student;
    public $weeklyJournals = [];
    public $weeklyTotal = '00:00';
    public $showModal = false;
    public $feedbackNote = '';
    public $workDays;
    public $dailyApprovals = [];
    public $selectedDate = null;
    public $dailyFeedback = [];
    public $dailyReopen = [];


    protected $listeners = ['openReportReview' => 'openModal'];

    public function openModal($reportId)
    {
        $this->reportId = $reportId;
        $this->loadReportData();
        $this->showModal = true;
    }

    // public function toggleDailyDetails($date)
    // {
    //     $this->selectedDate = $this->selectedDate === $date ? null : $date;
    // }

    // public function approveDailyEntry($date)
    // {
    //     $journal = $this->weeklyJournals[$date]['journal'];

    //     if ($journal) {
    //         DB::transaction(function () use ($journal) {
    //             // Update journal status
    //             $journal->update([
    //                 'is_approved' => 1,

    //                 'feedback' => $this->dailyFeedback[$date] ?? null,
    //                 'reviewed_at' => now(),
    //             ]);

    //             // Update attendance status
    //             if ($journal->attendance) {
    //                 $journal->attendance->update([
    //                     'is_approved' => 1,

    //                 ]);
    //             }
    //         });

    //         $this->loadJournals(); // Refresh the journals data
    //     }
    // }

    // public function rejectDailyEntry($date)
    // {
    //     $this->validate([
    //         "dailyFeedback.$date" => 'required|min:10',
    //     ], [
    //         "dailyFeedback.$date.required" => 'Please provide feedback before rejecting the entry.',
    //         "dailyFeedback.$date.min" => 'Feedback should be at least 10 characters long.'
    //     ]);

    //     $journal = $this->weeklyJournals[$date]['journal'];

    //     if ($journal) {
    //         DB::transaction(function () use ($journal, $date) {
    //             // Update journal status
    //             $journal->update([
    //                 'is_approved' => 2,

    //                 'is_reopened' => $this->dailyReopen[$date] ?? false,
    //                 'feedback' => $this->dailyFeedback[$date],
    //                 'reviewed_at' => now(),

    //             ]);

    //             // Update attendance status
    //             if ($journal->attendance) {
    //                 $journal->attendance->update([
    //                     'is_approved' => 2,

    //                 ]);
    //             }
    //         });

    //         $this->loadJournals(); // Refresh the journals data
    //     }
    // }

    public function loadReportData()
    {
        $this->report = Report::with('student.deployment')->findOrFail($this->reportId);
        $this->student = $this->report->student;
        $this->workDays = $this->student->deployment->work_days ?? 5;

        // Load journals
        $this->loadJournals();

        // Calculate total hours
        $this->calculateWeeklyTotal();
        $this->initializeDailyApprovals();
    }
    protected function initializeDailyApprovals()
    {
        foreach ($this->weeklyJournals as $date => $data) {
            if ($data['journal']) {
                $this->dailyApprovals[$date] = [
                    'journal_status' => $data['journal']->is_approved ?? 0,
                    'attendance_status' => $data['journal']->attendance->is_approved ?? 0
                ];
            }
        }
    }

    // public function approveDay($date)
    // {
    //     if (isset($this->weeklyJournals[$date]['journal'])) {
    //         $journal = $this->weeklyJournals[$date]['journal'];
    //         $attendance = $journal->attendance;

    //         if ($journal) {
    //             $journal->update(['is_approved' => 1]);
    //         }
    //         if ($attendance) {
    //             $attendance->update(['is_approved' => 1]);
    //         }

    //         $this->dailyApprovals[$date] = [
    //             'journal_status' => 1,
    //             'attendance_status' => 1
    //         ];
    //     }
    // }

    // public function approveAll()
    // {
    //     foreach ($this->weeklyJournals as $date => $data) {
    //         if ($data['journal']) {
    //             $this->approveDay($date);
    //         }
    //     }
    // }

    // public function rejectAll()
    // {
    //     foreach ($this->weeklyJournals as $date => $data) {
    //         if ($data['journal']) {
    //             $this->rejectDay($date);
    //         }
    //     }
    // }

    // public function rejectDay($date)
    // {
    //     if (isset($this->weeklyJournals[$date]['journal'])) {
    //         $journal = $this->weeklyJournals[$date]['journal'];
    //         $attendance = $journal->attendance;

    //         if ($journal) {
    //             $journal->update(['is_approved' => 2]);
    //         }
    //         if ($attendance) {
    //             $attendance->update(['is_approved' => 2]);
    //         }

    //         $this->dailyApprovals[$date] = [
    //             'journal_status' => 2,
    //             'attendance_status' => 2
    //         ];
    //     }
    // }

    protected function loadJournals()
    {
        $this->weeklyJournals = collect();
        $currentDate = Carbon::parse($this->report->start_date)->startOfDay();
        $endDate = Carbon::parse($this->report->end_date)->endOfDay();

        // Get all journals with relationships for the date range
        $journals = Journal::with([
            'attendance',
            'taskHistories.task'
        ])
            ->where('student_id', $this->student->id)
            ->whereBetween('date', [
                $currentDate->format('Y-m-d'),
                $endDate->format('Y-m-d')
            ]);

        $journalsByDate = $journals->get()->keyBy(function ($journal) {
            return $journal->date->format('Y-m-d');
        });

        // Create entries for each day in the range
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $journal = $journalsByDate->get($dateString);

            // Calculate daily total if attendance exists
            $dailyTotal = '00:00';
            if ($journal && $journal->attendance) {
                $dailyTotal = $journal->attendance->total_hours ?? '00:00';
            }

            // Process tasks for this journal
            $tasks = collect();
            if ($journal) {
                $taskHistories = $journal->taskHistories;
                $groupedHistories = $taskHistories->groupBy('task_id');

                $tasks = $groupedHistories->map(function ($histories) {
                    $latestHistory = $histories->sortByDesc('changed_at')->first();
                    $task = $latestHistory->task;

                    return [
                        'title' => $task->title,
                        'description' => $task->description,
                        'status' => $latestHistory->status,
                        'worked_hours' => $latestHistory->worked_hours,
                        'remarks' => $latestHistory->remarks
                    ];
                })->values();
            }

            $this->weeklyJournals[$dateString] = [
                'journal' => $journal,
                'daily_total' => $dailyTotal,
                'tasks' => $tasks
            ];

            $currentDate->addDay();
        }
    }

    protected function calculateWeeklyTotal()
    {
        // Calculate total hours for the selected date range
        $attendances = Attendance::where('student_id', $this->student->id)
            ->whereBetween('date', [$this->report->start_date, $this->report->end_date]);

        // Apply work days filter
        if ($this->workDays === 5) {
            $attendances->whereRaw("DAYOFWEEK(date) NOT IN (1, 7)");
        } else {
            $attendances->whereRaw("DAYOFWEEK(date) != 1");
        }

        // Sum up the hours
        $totalMinutes = 0;
        $attendances->get()->each(function ($attendance) use (&$totalMinutes) {
            if ($attendance->total_hours) {
                list($hours, $minutes) = array_pad(explode(':', $attendance->total_hours), 2, 0);
                $totalMinutes += ((int)$hours * 60) + (int)$minutes;
            }
        });

        // Format the result
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        $this->weeklyTotal = sprintf("%02d:%02d", $hours, $minutes);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['reportId', 'report', 'student', 'weeklyJournals', 'weeklyTotal', 'feedbackNote']);
    }

    public function approveReport()
    {
        if (!$this->report) {
            return;
        }

        $this->report->status = 'approved';
        $this->report->reviewed_at = now();
        $this->report->supervisor_feedback = $this->feedbackNote;
        $this->report->save();
        $start = Carbon::parse($this->report->start_date)->format('F j, Y');
        $end = Carbon::parse($this->report->end_date)->format('F j, Y');
        Notification::send(
            $this->report->student->user->id,
            'approved_weekly_report',
            'Weekly Report Approved',
            "Your Week {$this->report->week_number} ({$start}-{$end}) has been approved.",
            'student.taskAttendance',
            'fa-check-circle',
        );
        $this->closeModal();
        $this->dispatch('reportStatusChanged');
    }

    public function rejectReport()
    {
        if (!$this->report) {
            return;
        }

        $this->report->status = 'rejected';
        $this->report->reviewed_at = now();
        $this->report->supervisor_feedback = $this->feedbackNote;
        $this->report->save();
        $start = Carbon::parse($this->report->start_date)->format('F j, Y');
        $end = Carbon::parse($this->report->end_date)->format('F j, Y');
        Notification::send(
            $this->report->student->user->id,
            'rejected_weekly_report',
            'Weekly Report Rejected',
            "Your Week {$this->report->week_number} ({$start}-{$end}) has been rejected. Please review the feedback provided.",
            'student.taskAttendance',
            'fa-check-circle',
        );
        $this->closeModal();
        $this->dispatch('reportStatusChanged');
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

    public function render()
    {
        return view('livewire.supervisor.weekly-report-review');
    }
}
