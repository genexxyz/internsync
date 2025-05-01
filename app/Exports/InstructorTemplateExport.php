<?php

namespace App\Exports;

class InstructorTemplateExport
{
    public static function getHeaders()
    {
        return [
            'instructor_id',
            'email',
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'contact',
            'course_code',
            'is_program_head',
            'handling_sections'
        ];
    }

    public static function getSampleData()
    {
        return [
            'INST-2024-001',
            'juan@example.com',
            'Juan',
            'Santos',
            'Dela Cruz',
            'Jr',
            '09123456789',
            'BSIT,BSCS', // Multiple program head courses
            'YES',
            'BSIT-4A,BSIT-4B, ACT-2A' // Course-specific sections
        ];
    }
}