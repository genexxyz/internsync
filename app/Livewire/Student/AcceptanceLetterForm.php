<?php

namespace App\Livewire\Student;

use App\Models\AcceptanceLetter;
use App\Models\Student;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use LivewireUI\Modal\ModalComponent;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class AcceptanceLetterForm extends ModalComponent
{
    use WithFileUploads;
    public $company_name;
    public $department_name;
    public $supervisor_name;
    public $address;
    public $contact;
    public $email;
    public $acceptance_letter;

    protected $rules = [
        'company_name' => 'required|string|max:255',
        'department_name'=> 'nullable|string|max:255',
        'supervisor_name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'contact' => 'required|string|max:255',
        'email'=> 'string|email|max:255',
    ];

    public function generatePDF()
    {
        $this->validate();

        $student = Student::where('user_id', Auth::id())
            ->with([
                'section.course',
                'section.course.instructorCourses' => function ($query) {
                    $query->where('is_verified', true)
                        ->with('instructor.user');
                },
                'deployment' => function ($query) {
                    $query->select('student_id', 'custom_hours');
                }
            ])
            ->firstOrFail();

        $letter = AcceptanceLetter::create([
            'student_id' => $student->id,
            'company_name' => $this->company_name,
            'department_name' => $this->department_name,
            'supervisor_name' => $this->supervisor_name,
            'address' => $this->address,
            'contact' => $this->contact,
            'email' => $this->email,
            'is_generated' => true
        ]);

        $studentFullName = $student->last_name . '-' . $student->first_name;
        // Get program head name
        $programHead = $student->section->course->instructorCourses->first()?->instructor->first_name . ' ' . $student->section->course->instructorCourses->first()?->instructor->last_name ?? 'N/A';

        // Get required training hours
        $requiredHours = $student->deployment->custom_hours ?? 'N/A';
        $this->closeModal();
        // $pdf = PDF::loadView('pdfs.acceptance-letter', [
        //     'letter' => $letter,
        //     'student' => $student,
        //     'programHead' => $programHead,
        //     'requiredHours' => $requiredHours
        // ]);

        // return response()->streamDownload(
        //     fn() => print($pdf->output()),
        //     $studentFullName . '_acceptance-letter.pdf'
        // );
    }

    // public function uploadSignedLetter()
    // {
    //     $this->validate([
    //         'signed_letter' => 'required|file|mimes:pdf|max:10240'
    //     ]);

    //     try {
    //         $student = Student::where('user_id', Auth::id())
    //             ->with(['section.course'])
    //             ->firstOrFail();

    //         $fileName = implode('-', [
    //             $student->student_id,
    //             $student->last_name,
    //             $student->first_name,
    //             $student->section->course->course_code,
    //             'acceptance-letter-signed',
    //             Str::random(8)
    //         ]) . '.pdf';

    //         // Store the file
    //         $path = $this->signed_letter->storeAs(
    //             'acceptance_letters',
    //             $fileName,
    //             'public'
    //         );

    //         // Update the acceptance letter record
    //         AcceptanceLetter::where('student_id', $student->id)
    //             ->update(['signed_path' => $path]);

    //         $this->closeModal();
    //         $this->dispatch('alert', type: 'success', text: 'Signed letter uploaded successfully!');
    //         $this->dispatch('refreshDocument');
    //     } catch (\Exception $e) {
    //         logger()->error('Error uploading signed letter', [
    //             'error' => $e->getMessage(),
    //             'student_id' => Auth::id()
    //         ]);
    //         $this->dispatch('alert', type: 'error', text: 'Error uploading signed letter.');
    //     }
    // }


    public function mount()
{
    $student = Student::where('user_id', Auth::id())->firstOrFail();
    $this->acceptance_letter = AcceptanceLetter::where('student_id', $student->id)->first();
}
    public function render()
    {
        return view('livewire.student.acceptance-letter-form');
    }
}
