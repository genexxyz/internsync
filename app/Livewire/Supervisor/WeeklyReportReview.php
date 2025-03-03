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

    public function render()
    {
        return view('livewire.supervisor.weekly-report-review');
    }
}