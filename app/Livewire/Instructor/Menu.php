<?php

namespace App\Livewire\Instructor;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Menu extends Component
{
    public $isOpen = false;

    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
    }

    public $instructor;
public $handledCourses;
public $isCourseHead;

public function mount()
{
    // Load instructor with related sections and user
    $this->instructor = Instructor::where("user_id", Auth::user()->id)->first();

    // Retrieve handled courses
    $this->handledCourses = Program::where('instructor_id', $this->instructor->id)
    ->where('is_verified', true)
    ->get();

    // Determine if the instructor is a course head
    $this->isCourseHead = $this->handledCourses->isNotEmpty();
}

    public function render()
    {
        return view('livewire.instructor.menu');
    }
}

