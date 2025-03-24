<?php

namespace App\Livewire\Supervisor;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use App\Models\Deployment;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationModal extends ModalComponent
{
    public $deployment;
    public $isEvaluated = false;
public $evaluation = null;
    public $ratings = [
        'quality_work' => null,
        'completion_time' => null,
        'dependability' => null,
        'judgment' => null,
        'cooperation' => null,
        'attendance' => null,
        'personality' => null,
        'safety' => null,
    ];

    // Add max ratings array
    public $maxRatings = [
        'quality_work' => 20,
        'completion_time' => 15,
        'dependability' => 15,
        'judgment' => 10,
        'cooperation' => 10,
        'attendance' => 10,
        'personality' => 10,
        'safety' => 10,
    ];

    public $recommendation = '';

    protected function rules()
    {
        return [
            'ratings.quality_work' => "required|numeric|min:1|max:{$this->maxRatings['quality_work']}",
            'ratings.completion_time' => "required|numeric|min:1|max:{$this->maxRatings['completion_time']}",
            'ratings.dependability' => "required|numeric|min:1|max:{$this->maxRatings['dependability']}",
            'ratings.judgment' => "required|numeric|min:1|max:{$this->maxRatings['judgment']}",
            'ratings.cooperation' => "required|numeric|min:1|max:{$this->maxRatings['cooperation']}",
            'ratings.attendance' => "required|numeric|min:1|max:{$this->maxRatings['attendance']}",
            'ratings.personality' => "required|numeric|min:1|max:{$this->maxRatings['personality']}",
            'ratings.safety' => "required|numeric|min:1|max:{$this->maxRatings['safety']}",
            'recommendation' => 'required|min:50|max:1000',
        ];
    }

    protected $messages = [
        'ratings.*.required' => 'This rating is required.',
        'ratings.*.numeric' => 'Rating must be a number.',
        'ratings.*.min' => 'Rating must be at least 1.',
        'ratings.*.max' => 'Rating cannot exceed the maximum percentage.',
        'recommendation.required' => 'Please provide a recommendation.',
        'recommendation.min' => 'Recommendation must be at least 50 characters.',
    ];
    public static function modalMaxWidth(): string
    {
        return '3xl'; // This makes the modal wider
    }
    public function mount(Deployment $deployment)
    {
        $this->deployment = $deployment->load(['student.user', 'student.section.course', 'department.company']);
        $this->evaluation = Evaluation::where('deployment_id', $deployment->id)->first();
        
        if ($this->evaluation) {
            $this->isEvaluated = true;
            $this->ratings = [
                'quality_work' => $this->evaluation->quality_work,
                'completion_time' => $this->evaluation->completion_time,
                'dependability' => $this->evaluation->dependability,
                'judgment' => $this->evaluation->judgment,
                'cooperation' => $this->evaluation->cooperation,
                'attendance' => $this->evaluation->attendance,
                'personality' => $this->evaluation->personality,
                'safety' => $this->evaluation->safety,
            ];
            $this->recommendation = $this->evaluation->recommendation;
        }
    }

public function getTotalRatingProperty()
    {
        return collect($this->ratings)
            ->map(function($rating) {
                return is_numeric($rating) ? (int)$rating : 0;
            })
            ->sum();
    }

    public function updatedRatings($value, $key)
    {
        // Convert to integer or set to null if invalid
        if (!is_numeric($value)) {
            data_set($this->ratings, $key, null);
            return;
        }

        $value = (int)$value;
        $maxRating = data_get($this->maxRatings, $key, 0);

        // Ensure value is within bounds
        if ($value < 1) {
            data_set($this->ratings, $key, 1);
        } elseif ($value > $maxRating) {
            data_set($this->ratings, $key, $maxRating);
        }
    }
    public function save()
{
    $this->validate();

    try {
        // Begin transaction
        DB::beginTransaction();

        // Create the evaluation
        $evaluation = Evaluation::create([
            'deployment_id' => $this->deployment->id,
            'supervisor_id' => Auth::user()->supervisor->id,
            'quality_work' => $this->ratings['quality_work'],
            'completion_time' => $this->ratings['completion_time'],
            'dependability' => $this->ratings['dependability'],
            'judgment' => $this->ratings['judgment'],
            'cooperation' => $this->ratings['cooperation'],
            'attendance' => $this->ratings['attendance'],
            'personality' => $this->ratings['personality'],
            'safety' => $this->ratings['safety'],
            'total_score' => $this->getTotalRatingProperty(),
            'recommendation' => $this->recommendation,
        ]);

        // Update deployment status to evaluated
        

        DB::commit();

        // Show success notification
        $this->dispatch('alert', 
            type: 'success',
            text: 'Evaluation submitted successfully!'
        );

        // Close modal and refresh parent component
        $this->dispatch('evaluation-saved');
        $this->closeModal();

    } catch (\Exception $e) {
        DB::rollBack();
        
        // Show error notification
        $this->dispatch('alert', 
            type: 'error',
            text: 'Failed to save evaluation. Please try again!'
        );

        // Log the error
        logger()->error('Evaluation save failed:', [
            'error' => $e->getMessage(),
            'deployment_id' => $this->deployment->id
        ]);
    }
}

public function generatePdf()
{
    $evaluation = $this->evaluation;
    $deployment = $this->deployment;
    
    $data = [
        'deployment' => $deployment,
        'ratings' => $this->ratings,
        'maxRatings' => $this->maxRatings,
        'recommendation' => $this->recommendation,
        'totalScore' => $this->getTotalRatingProperty(),
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

    $pdf = \PDF::loadView('pdfs.evaluation-report', $data);
    
    return response()->streamDownload(function() use ($pdf) {
        echo $pdf->output();
    }, 'Evaluation_Report_' . $deployment->student->last_name . '_' . $deployment->student->first_name . '.pdf');
}

    public function render()
    {
        return view('livewire.supervisor.evaluation-modal');
    }
}