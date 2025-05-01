<?php

namespace App\Livewire\Admin;

use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;
use App\Exports\InstructorTemplateExport;
use App\Imports\InstructorsImport;
use App\Models\Academic;

class ImportInstructorsModal extends ModalComponent
{
    use WithFileUploads;

    public $file;
    public $academic_id;
    public $importing = false;
    public $importErrors = [];

    public function mount()
    {
        $this->academic_id = Academic::where('ay_default', true)->first()?->id;
    }

    public function downloadTemplate()
    {
        $headers = InstructorTemplateExport::getHeaders();
        $sampleData = InstructorTemplateExport::getSampleData();
        
        $output = fopen('php://temp', 'w+');
        fputcsv($output, $headers);
        fputcsv($output, $sampleData);
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'instructors_template.csv');
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

            $import = new InstructorsImport($this->academic_id);
            $import->import($this->file->getRealPath());

            if (count($import->errors) > 0) {
                $this->importErrors = $import->errors;
                $this->dispatch('alert', 
                    type: 'error',
                    text: 'Import completed with some errors. Please check the error list.'
                );
            } else {
                $this->dispatch('alert', 
                    type: 'success',
                    text: 'Instructors imported successfully!'
                );
                $this->dispatch('refreshInstructors');
                $this->closeModal();
            }
        } catch (\Exception $e) {
            logger()->error('Import error:', ['error' => $e->getMessage()]);
            $this->addError('import', 'Error importing instructors: ' . $e->getMessage());
        } finally {
            $this->importing = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.import-instructors-modal', [
            'academics' => Academic::orderBy('academic_year', 'desc')->get()
        ]);
    }
}