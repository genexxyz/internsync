<?php

namespace App\Services;

use App\Models\Deployment;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LetterTemplate;
use Illuminate\Support\Str;

class DocumentGeneratorService
{
    public function generateAcceptanceLetter(Deployment $deployment)
    {
        $template = LetterTemplate::where('is_active', true)->first();
        
        if (!$template) {
            throw new \Exception('No active letter template found');
        }

        // Replace variables in template content
        $content = $template->content;
        $replacements = [
            '{school_name}' => Setting::where('id', 3)->first()->school_name,
            '{course_name}' => $deployment->student->section->course->course_name,
            '{required_hours}' => $deployment->custom_hours,
            '{student_name}' => $deployment->student->name(),
            '{supervisor_name}' => $deployment->supervisor->getFullNameAttribute(),
            '{company_name}' => $deployment->department->company->company_name,
            '{current_date}' => now()->format('F d, Y'),
        ];

        foreach ($replacements as $key => $value) {
            $content = str_replace($key, $value ?? '', $content);
        }

        $data = [
            'deployment' => $deployment,
            'studentName' => $deployment->student->name(),
            'settings' => Setting::where('id', 3)->first(),
            'signatureUrl' => $deployment->supervisor->signature_path,
            'supervisorName' => $deployment->supervisor->getFullNameAttribute(),
            'date' => now()->format('F d, Y'),
            'content' => $content
        ];

        $pdf = Pdf::loadView('pdfs.acceptance-letter', $data);
        
        return $pdf;
    }
}