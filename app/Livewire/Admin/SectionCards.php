<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Section;
use Livewire\Component;
use Livewire\Attributes\On;

class SectionCards extends Component
{
    public $courses;
    public $sections;
    public $instructors;

    #[On('refreshSections')] 
    public function refresh()
    {
        if ($this->courses) {
            $this->loadSections();
        }
    }

    public function mount($course_id)
    {
        $this->courses = Course::find($course_id);
        $this->loadSections();
    }

    private function loadSections()
    {
        $this->sections = Section::where('course_id', $this->courses->id)
            ->with(['handles.instructor.user' => function($query) {
                $query->where('is_verified', true);
            }])
            ->withCount('students')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.section-cards');
    }
}