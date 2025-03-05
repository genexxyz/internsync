<?php

namespace App\Livewire\Supervisor;

use App\Models\Report;
use App\Models\Journal;
use App\Models\Attendance;
use Carbon\Carbon;
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
    
    protected $listeners = ['openReportReview' => 'openModal'];

    public function openModal($reportId)
    {
        $this->reportId = $reportId;
        $this->loadReportData();
        $this->showModal = true;
    }

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

    public function approveDay($date)
    {
        if (isset($this->weeklyJournals[$date]['journal'])) {
            $journal = $this->weeklyJournals[$date]['journal'];
            $attendance = $journal->attendance;

            if ($journal) {
                $journal->update(['is_approved' => 1]);
            }
            if ($attendance) {
                $attendance->update(['is_approved' => 1]);
            }

            $this->dailyApprovals[$date] = [
                'journal_status' => 1,
                'attendance_status' => 1
            ];
        }
    }

    public function approveAll()
    {
        foreach ($this->weeklyJournals as $date => $data) {
            if ($data['journal']) {
                $this->approveDay($date);
            }
        }
    }

    public function rejectAll()
    {
        foreach ($this->weeklyJournals as $date => $data) {
            if ($data['journal']) {
                $this->rejectDay($date);
            }
        }
    }

    public function rejectDay($date)
    {
        if (isset($this->weeklyJournals[$date]['journal'])) {
            $journal = $this->weeklyJournals[$date]['journal'];
            $attendance = $journal->attendance;

            if ($journal) {
                $journal->update(['is_approved' => 2]);
            }
            if ($attendance) {
                $attendance->update(['is_approved' => 2]);
            }

            $this->dailyApprovals[$date] = [
                'journal_status' => 2,
                'attendance_status' => 2
            ];
        }
    }
    protected function loadJournals()
    {
        $this->weeklyJournals = collect();
        $currentDate = Carbon::parse($this->report->start_date);
        $endDate = Carbon::parse($this->report->end_date);

        // Get all journals for the week with their attendances
        $journals = Journal::with('attendance')
            ->where('student_id', $this->student->id)
            ->whereBetween('date', [$this->report->start_date, $this->report->end_date]);

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
        $attendances->get()->each(function($attendance) use (&$totalMinutes) {
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