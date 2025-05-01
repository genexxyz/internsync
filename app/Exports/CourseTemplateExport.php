<?php

namespace App\Exports;

class CourseTemplateExport
{
    public static function getHeaders()
    {
        return [
            'Course Code',
            'Course Name',
            'Required Hours',
            'Allows Custom Hours',
            'Custom Hours',
            'Sections'
        ];
    }

    public static function getSampleData()
    {
        return [
            'BSIT',
            'Bachelor of Science in Information Technology',
            '500',
            'YES',
            '250',
            '4A,4B,4C'
        ];
    }
}