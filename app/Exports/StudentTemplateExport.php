<?php

namespace App\Exports;

class StudentTemplateExport
{
    public static function getHeaders()
    {
        return [
            'student_id',
            'email',
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'contact',
            'address',
            'course_section', // Format: BSIT-4A
            'type' // regular or special
        ];
    }

    public static function getSampleData()
    {
        return [
            '2024-0001',
            'student@example.com',
            'Juan',
            'Santos',
            'Dela Cruz',
            'Jr',
            '09123456789',
            '123 Main St, Brgy. Example, City, Province',
            'BSIT-4A',
            'regular'
        ];
    }
}