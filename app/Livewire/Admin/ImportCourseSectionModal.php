<?php

namespace App\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;
use App\Exports\CourseTemplateExport;
use App\Imports\CourseSectionImport;
use App\Models\Academic;

class ImportCourseSectionModal extends ModalComponent
{
    use WithFileUploads;

    public $file;
    public $academic_id;
    public $importing = false;
    public $importErrors = [];

    public function mount()
    {
        $this->academic_id = Academic::where('status', 1)->first()?->id;
    }

    public function downloadTemplate()
    {
        $headers = CourseTemplateExport::getHeaders();
        $sampleData = CourseTemplateExport::getSampleData();
        
        $output = fopen('php://temp', 'w+');
        fputcsv($output, $headers);
        fputcsv($output, $sampleData);
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'courses_template.csv');
    }

    public function import()
    {
        $this->importing = true;
        $this->importErrors = [];

        try {
            $this->validate([
                'file' => 'required|file|mimes:csv,txt|max:2048',
                'academic_id' => 'required|exists:academics,id'
            ]);

            $importer = new CourseSectionImport($this->academic_id);
            $result = $importer->import($this->file->getRealPath());

            if (!$result['success']) {
                $this->importErrors = $result['errors'];
                $this->dispatch('alert', 
                    type: 'error',
                    text: 'Import completed with some errors. Please check the error list.'
                );
            } else {
                $this->dispatch('alert', 
                    type: 'success',
                    text: 'Courses and sections imported successfully!'
                );
                $this->dispatch('refreshCourses');
                $this->closeModal();
            }
        } catch (\Exception $e) {
            logger()->error('Import error:', ['error' => $e->getMessage()]);
            $this->addError('import', 'Error importing courses: ' . $e->getMessage());
        } finally {
            $this->importing = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.import-course-section-modal', [
            'academics' => Academic::where('status', 1)
                ->orderBy('academic_year', 'desc')
                ->get()
        ]);
    }
}