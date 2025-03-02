<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;
use Livewire\Attributes\On;

class CourseCards extends Component
{
    public $search = '';
    public $query;

    #[On('refreshCourses')] 
    public function refresh()
    {
        // Reset the search term
        $this->search = '';
        
        // This will trigger a re-render of the component
        $this->dispatch('coursesRefreshed');
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function render()
    {
        // Start the query to order by course_name
        $query = Course::query()->orderBy('course_name', 'asc');

        // Apply search filter if search term is provided
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('course_name', 'like', '%' . $this->search . '%')
                  ->orWhere('course_code', 'like', '%' . $this->search . '%');
            });
        }

        // Fetch the results
        $courses = $query->get();

        return view('livewire.admin.course-cards', [
            'courses' => $courses
        ]);
    }
}
