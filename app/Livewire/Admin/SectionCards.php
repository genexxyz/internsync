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
    public $editingSection = null;
    public $editableData = [
        'class_section' => '',
    ];

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

    public function startEditing($sectionId)
    {
        $section = Section::find($sectionId);
        $this->editingSection = $sectionId;
        $this->editableData['class_section'] = $section->class_section;
    }

    public function cancelEditing()
    {
        $this->editingSection = null;
        $this->editableData['class_section'] = '';
    }

    public function saveSectionChanges()
    {
        $section = Section::find($this->editingSection);
        
        $this->validate([
            'editableData.class_section' => [
                'required',
                'string',
                'max:1',
                'regex:/^[A-Z]$/',
                function ($attribute, $value, $fail) use ($section) {
                    $exists = Section::where('course_id', $this->courses->id)
                        ->where('year_level', $section->year_level)
                        ->where('class_section', $value)
                        ->where('id', '!=', $this->editingSection)
                        ->exists();
                    
                    if ($exists) {
                        $fail("This section already exists for {$this->courses->course_code} {$section->year_level}.");
                    }
                },
            ],
        ], [
            'editableData.class_section.regex' => 'Section must be a single uppercase letter (A-Z).'
        ]);

        try {
            $section->update([
                'class_section' => $this->editableData['class_section'],
            ]);

            $this->cancelEditing();
            $this->dispatch('alert', type: 'success', text: 'Section updated successfully!');
            $this->loadSections();
            
        } catch (\Exception $e) {
            logger()->error('Error updating section', [
                'error' => $e->getMessage(),
                'section_id' => $this->editingSection
            ]);
            $this->dispatch('alert', type: 'error', text: 'Error updating section.');
        }
    }
    public function deleteSection($sectionId)
{
    try {
        $section = Section::findOrFail($sectionId);
        
        // Check if section has any students
        if ($section->students()->exists()) {
            $this->dispatch('alert', type: 'error', text: 'Cannot delete section with enrolled students.');
            return;
        }

        // Check if section has any handles (instructors)
        if ($section->handles()->exists()) {
            $this->dispatch('alert', type: 'error', text: 'Cannot delete section with assigned instructors.');
            return;
        }
        
        $section->delete();
        $this->cancelEditing();
        $this->dispatch('alert', type: 'success', text: 'Section deleted successfully!');
        $this->loadSections();
        
    } catch (\Exception $e) {
        logger()->error('Error deleting section', [
            'error' => $e->getMessage(),
            'section_id' => $sectionId
        ]);
        $this->dispatch('alert', type: 'error', text: 'Error deleting section.');
    }
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