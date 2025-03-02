<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Academic;
use LivewireUI\Modal\ModalComponent;

class AddCourseModal extends ModalComponent
{
    public $instructors;
    public $academicYear;
    
    public $course_name, $course_code, $required_hours, $custom_hours, $academic_year, $year_level, $instructor_id;
    protected $listeners = [
        'valueSingleUpdated' => 'updatedSingleSelection',
    ];
    protected $rules = [
        'course_name' => 'required|string|unique:courses,course_name',
        'course_code' => 'required|string|unique:courses,course_code',
        'required_hours' => 'required|integer|min:2',
        'custom_hours' => 'nullable|integer|min:2',
        'academic_year' => 'required|integer',
        // 'instructor_id' => 'nullable|integer',
    ];
    public function updatedSingleSelection($value)
    {
        
                $this->academic_year = $value;
            
    }
    public function saveCourse()
    {
        $this->validate();

        Course::create([
            'course_name' => $this->course_name,
            'course_code' => $this->course_code,
            'required_hours' => $this->required_hours,
            'custom_hours' => $this->custom_hours,
            'academic_year_id' => $this->academic_year,
            // 'instructor_id' => $this->instructor_id,
        ]);
        
        $this->resetForm();
        $this->closeModal();
        $this->dispatch('refreshCourses');
        $this->dispatch('alert', type:'success', text:'Course Added Successfully!');
        
    }

    public function resetForm()
    {
        $this->reset(['course_name', 'course_code', 'required_hours', 'custom_hours', 'academic_year']);
        
        
    }

    public function mount()
    {
        // Fetching all instructors and academic years
        $this->instructors = Instructor::all();
        $this->academicYear = Academic::all();
    }

    public function render()
    {
        return view('livewire.admin.add-course-modal');
    }
}
