<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\LetterTemplate;
use LivewireUI\Modal\ModalComponent;

class LetterTemplateManager extends ModalComponent
{
    public $templates;
    public $editingTemplate;
    public $templateContent;
    public $templateTitle;
    public $isEditing = false;
    public $availableVariables = [
        '{school_name}' => 'School Name',
        '{course_name}' => 'Course Name',
        // '{required_hours}' => 'Required Hours',
        '{student_name}' => 'Student Name',
        // '{student_gender}' => 'Student Gender (he/she)',
        '{supervisor_name}' => 'Supervisor Name',
        '{company_name}' => 'Company Name',
        '{current_date}' => 'Current Date',
        // '{student_type}' => 'Student Type',
        '{required_hours}' => 'Required Hours',
    ];

    public function mount()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $this->templates = LetterTemplate::orderBy('created_at', 'desc')->get();
    }

    public function createTemplate()
    {
        $this->isEditing = false;
        $this->templateContent = '';
        $this->templateTitle = '';
        $this->editingTemplate = null;
    }

    public function editTemplate($id)
    {
        $this->editingTemplate = LetterTemplate::find($id);
        $this->templateContent = $this->editingTemplate->content;
        $this->templateTitle = $this->editingTemplate->title;
        $this->isEditing = true;
    }

    public function saveTemplate()
    {
        $this->validate([
            'templateTitle' => 'required|string|max:255',
            'templateContent' => 'required|string'
        ]);

        if ($this->isEditing) {
            $this->editingTemplate->update([
                'title' => $this->templateTitle,
                'content' => $this->templateContent,
                'variables' => array_keys($this->availableVariables)
            ]);
        } else {
            LetterTemplate::create([
                'title' => $this->templateTitle,
                'content' => $this->templateContent,
                'variables' => array_keys($this->availableVariables)
            ]);
        }

        $this->loadTemplates();
        $this->dispatch('alert', type: 'success', text: 'Template saved successfully!');
        $this->reset(['templateContent', 'templateTitle', 'isEditing']);
        $this->dispatch('closeModal');
    }

    public function setActive($id)
    {
        LetterTemplate::where('is_active', true)->update(['is_active' => false]);
        LetterTemplate::find($id)->update(['is_active' => true]);
        $this->loadTemplates();
        $this->dispatch('alert', type: 'success', text: 'Template activated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.letter-template-manager');
    }
}