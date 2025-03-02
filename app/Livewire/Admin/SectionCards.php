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
        // Reload sections for the current course
        if ($this->courses) {
            $this->sections = Section::where('course_id', $this->courses->id)->get();
        }
    }
    public function mount($course_id)
{
    $this->courses = Course::find($course_id);
    $this->sections = Section::where('course_id', $course_id)->get();
    $this->instructors = Instructor::all();
}

    public function render()
    {
        return view('livewire.admin.section-cards');
    }
}
