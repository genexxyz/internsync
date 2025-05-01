<?php
namespace App\Livewire\Instructor;

use App\Models\Journal;
use App\Models\Report;
use App\Models\Student;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Models\Setting;
use App\Services\DocumentGeneratorService;
use Illuminate\Support\Str;
class StudentModal extends ModalComponent
{
    public Student $student;
    public $selectedTab = 'info';
    public $isEditing = false;
    public $isEditingDeployment = false;
    public $deploymentData = [
        'custom_hours' => null,
        'student_type' => 'regular',
        'permit_path' => null
    ];
    public $permitFile;
    public $editableData = [
        'first_name' => '',
        'middle_name' => '',
        'last_name' => '',
        'suffix' => '',
        'student_id' => '',
        'contact' => '',
        'address' => '',
    ];
    public static function modalMaxWidth(): string
    {
        return '2xl';
    }
    protected $documentGenerator;
    public function boot(DocumentGeneratorService $documentGenerator)
    {
        $this->documentGenerator = $documentGenerator;
    }

    public function mount(Student $student)
    {
        $this->student = $student->load([
            'user',
            'section.course',
            'deployment.company',
            'deployment.supervisor',
            'deployment.department',
            'deployment.evaluation', // Add this line
            'acceptance_letter',
            'weeklyReports' => function($query) {
                $query->orderBy('week_number', 'desc');
            }
        ]);

        $this->editableData = [
            'first_name' => $this->student->first_name,
            'middle_name' => $this->student->middle_name,
            'last_name' => $this->student->last_name,
            'suffix' => $this->student->suffix,
            'student_id' => $this->student->student_id,
            'contact' => $this->student->contact,
            'address' => $this->student->address,
        ];
        if ($this->student->deployment) {
            $this->deploymentData = [
                'custom_hours' => $this->student->deployment->custom_hours,
                'student_type' => $this->student->deployment->student_type ?? 'regular',
                'permit_path' => $this->student->deployment->permit_path
            ];
        }
    }
    public function toggleDeploymentEdit()
    {
        $this->isEditingDeployment = !$this->isEditingDeployment;
    }

    public function saveDeployment()
    {
        if (!$this->student->deployment) {
            return;
        }

        $this->validate([
            'deploymentData.custom_hours' => 'nullable|integer|min:1|max:999',
            'deploymentData.student_type' => 'required|in:regular,special',
            'permitFile' => $this->deploymentData['student_type'] === 'special' ? 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048' : 'nullable',
        ]);

        try {
            $deployment = $this->student->deployment;

            if ($this->permitFile && $this->deploymentData['student_type'] === 'special') {
                // Delete old permit if exists
                if ($deployment->permit_path) {
                    Storage::disk('public')->delete($deployment->permit_path);
                }
                
                // Store new permit
                $path = $this->permitFile->store('permits', 'public');
                $this->deploymentData['permit_path'] = $path;
            }

            $deployment->update([
                'custom_hours' => $this->deploymentData['custom_hours'],
                'student_type' => $this->deploymentData['student_type'],
                'permit_path' => $this->deploymentData['permit_path'],
            ]);

            $this->student = $this->student->fresh(['deployment']);
            $this->isEditingDeployment = false;
            $this->dispatch('alert', type: 'success', text: 'Deployment updated successfully!');

        } catch (\Exception $e) {
            logger()->error('Error updating deployment', [
                'error' => $e->getMessage(),
                'deployment_id' => $this->student->deployment->id
            ]);
            $this->dispatch('alert', type: 'error', text: 'Error updating deployment.');
        }
    }
    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function saveChanges()
    {
        $this->validate([
            'editableData.first_name' => 'required|string|max:255',
            'editableData.last_name' => 'required|string|max:255',
            'editableData.student_id' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Student::where('student_id', $value)
                        ->where('id', '!=', $this->student->id)
                        ->exists();
                    
                    if ($exists) {
                        $fail('This student ID is already taken.');
                    }
                },
            ],
            'editableData.contact' => 'required|string|max:255',
            'editableData.address' => 'required|string|max:255',
        ]);

        try {
            $this->student->update([
                'first_name' => $this->editableData['first_name'],
                'middle_name' => $this->editableData['middle_name'],
                'last_name' => $this->editableData['last_name'],
                'suffix' => $this->editableData['suffix'],
            ]);

            $this->student->update([
                'student_id' => $this->editableData['student_id'],
                'contact' => $this->editableData['contact'],
                'address' => $this->editableData['address'],
            ]);
            
            $this->isEditing = false;
            $this->dispatch('alert', type: 'success', text: 'Student updated successfully!');
            $this->dispatch('refreshStudents');
        } catch (\Exception $e) {
            logger()->error('Error updating student', [
                'error' => $e->getMessage(),
                'student' => $this->student->id,
                'data' => $this->editableData
            ]);
            $this->dispatch('alert', type: 'error', text: 'Error updating student.');
        }
    }

    public function setTab($tab)
    {
        $this->selectedTab = $tab;
    }

    public function downloadAcceptanceLetter()
{
    try {
        if (!$this->student->deployment->supervisor_id) {
            $this->dispatch('alert', type: 'error', text: 'Student is not yet deployed.');
            return;
        }

        // if (!$this->student->deployment->acceptance_letter_signed) {
        //     $this->dispatch('alert', type: 'error', text: 'Acceptance letter is not yet signed.');
        //     return;
        // }

        $pdf = $this->documentGenerator->generateAcceptanceLetter($this->student->deployment);
        
        $filename = sprintf(
            'acceptance_letter_%s_%s_%s.pdf',
            $this->student->student_id,
            Str::slug($this->student->last_name),
            Str::slug($this->student->first_name)
        );

        return response()->streamDownload(
            function() use ($pdf) { 
                echo $pdf->output(); 
            },
            $filename
        );
    } catch (\Exception $e) {
        logger()->error('Error generating acceptance letter', [
            'error' => $e->getMessage(),
            'student_id' => $this->student->id,
            'deployment_id' => $this->student->deployment?->id
        ]);
        $this->dispatch('alert', type: 'error', text: 'Error generating acceptance letter.');
    }
}

    
    public function generateWeeklyReport(Report $report)
{
    try {
        // Get journals with tasks and attendance for the week, excluding rejected entries
        $journals = Journal::whereBetween('date', [$report->start_date, $report->end_date])
            ->where('student_id', $report->student_id)
            ->where(function($query) {
                $query->where('is_approved', 1)  // Approved journals
                      ->orWhereNull('is_approved'); // Pending journals
            })
            ->with([
                'attendance',
                'taskHistories' => function($query) {
                    $query->orderBy('created_at', 'asc');
                },
                'taskHistories.task'
            ])
            ->orderBy('date')
            ->get();
        
        // Calculate total hours
        $totalMinutes = $journals->reduce(function ($total, $journal) {
            if ($journal->attendance && $journal->attendance->total_hours) {
                list($hours, $minutes) = array_pad(explode(':', $journal->attendance->total_hours), 2, 0);
                return $total + ((int)$hours * 60) + (int)$minutes;
            }
            return $total;
        }, 0);
        
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        $formattedTotal = sprintf("%d hours and %d minutes", $hours, $minutes);
        
        // Get deployment relationships
        $student = $report->student->load(['deployment.department.company', 'deployment.supervisor', 'section.course.instructorCourses.instructor']);
        
        // Group task histories by journal date and get latest status
        $journals->each(function ($journal) {
            $journal->taskHistories = $journal->taskHistories
                ->groupBy('task_id')
                ->map(function ($histories) {
                    $latestHistory = $histories->last();
                    $latestHistory->task->current_status = $latestHistory->status;
                    return $latestHistory;
                })->values();
        });
        
        // Prepare data for PDF
        $data = [
            'report' => $report,
            'journals' => $journals,
            'student' => $student,
            'deployment' => $student->deployment,
            'company' => $student->deployment?->department?->company,
            'totalHours' => $formattedTotal,
            'startDate' => Carbon::parse($report->start_date)->format('M d, Y'),
            'endDate' => Carbon::parse($report->end_date)->format('M d, Y'),
            'settings' => Setting::first()
        ];
        
        // Generate PDF
        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadView('pdfs.weekly-report', $data);
        
        // Return download response
        return response()->streamDownload(
            function() use ($pdf) { 
                echo $pdf->output(); 
            },
            'Weekly_Report_Week_' . $report->week_number . '_' . $student->student_id . '_' . $student->last_name . '_' . $student->first_name . '.pdf'
        );

    } catch (\Exception $e) {
        logger()->error('Error generating weekly report', [
            'error' => $e->getMessage(),
            'report_id' => $report->id,
            'student_id' => $report->student_id
        ]);
        
        $this->dispatch('alert', type: 'error', text: 'Error generating weekly report.');
        return null;
    }
}
public function downloadEvaluation()
    {
        if (!$this->student->deployment?->evaluation) {
            return;
        }

        $evaluation = $this->student->deployment->evaluation;
        $deployment = $this->student->deployment;

        $data = [
            'deployment' => $deployment,
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

        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadView('pdfs.evaluation-report', $data);
        
        return response()->streamDownload(
            function() use ($pdf) { 
                echo $pdf->output(); 
            },
            'Evaluation_Report_' . $this->student->student_id . '_' . $this->student->last_name . '_' . $this->student->first_name . '.pdf'
        );
    }

    public function verifyStudent()
{
    try {
        $this->student->user->update([
            'is_verified' => true,
            'email_verified_at' => now()
        ]);
        
        $this->student = $this->student->fresh(['user']);
        $this->dispatch('alert', type: 'success', text: 'Student verified successfully!');
    } catch (\Exception $e) {
        logger()->error('Error verifying student', [
            'error' => $e->getMessage(),
            'student_id' => $this->student->id
        ]);
        $this->dispatch('alert', type: 'error', text: 'Error verifying student.');
    }
}



public function deleteStudent()
{
    try {
        // Check for dependencies
        if ($this->student->deployment()->exists()) {
            $this->dispatch('alert', type: 'error', text: 'Cannot delete student with existing deployment.');
            return;
        }

        // Delete related records
        $this->student->user->delete();
        $this->student->delete();
        
        $this->dispatch('alert', type: 'success', text: 'Student deleted successfully!');
        $this->dispatch('refreshStudents');
        $this->dispatch('closeModal');
    } catch (\Exception $e) {
        logger()->error('Error deleting student', [
            'error' => $e->getMessage(),
            'student_id' => $this->student->id
        ]);
        $this->dispatch('alert', type: 'error', text: 'Error deleting student.');
    }
}
    public function render()
    {
        return view('livewire.instructor.student-modal');
    }
}