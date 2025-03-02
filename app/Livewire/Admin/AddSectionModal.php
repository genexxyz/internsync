<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Section;
use LivewireUI\Modal\ModalComponent;

class AddSectionModal extends ModalComponent
{
    public $instructors;
    public Course $user; // Expecting the course ID
    public $year_level, $sections;

    protected $rules = [
        
        'year_level' => 'required|integer',
        'sections' => 'nullable|integer',
    ];

    

    public function saveSection()
    {
        $this->validate();

        // Get the last section for this course and year level
        $lastSection = Section::where('course_id', $this->user->id)
            ->where('year_level', $this->year_level)
            ->orderBy('class_section', 'desc')
            ->first();

        // Start with 'A' if no sections exist, or get next letter
        $startChar = $lastSection ? chr(ord($lastSection->class_section) + 1) : 'A';

        // Create sections based on input number
        for ($i = 0; $i < $this->sections; $i++) {
            $sectionLetter = chr(ord($startChar) + $i);
            
            // Check if this specific section already exists
            $exists = Section::where('course_id', $this->user->id)
                ->where('year_level', $this->year_level)
                ->where('class_section', $sectionLetter)
                ->exists();

            if (!$exists) {
                Section::create([
                    'course_id' => $this->user->id,
                    'year_level' => $this->year_level,
                    'class_section' => $sectionLetter
                ]);
            }
        }

        $this->resetForm();
        $this->closeModal();
        $this->dispatch('refreshSections');
        $this->dispatch('alert', type: 'success', text: 'Sections Added Successfully!');
        
    }

    public function resetForm()
    {
        $this->reset(['sections', 'year_level']);
    }

    public function render()
    {
        return view('livewire.admin.add-section-modal');
    }
}
