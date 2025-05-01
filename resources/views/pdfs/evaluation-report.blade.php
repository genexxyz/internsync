<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Performance Evaluation Report</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .header1 {
            text-align: center;
            margin-bottom: 20px;
            margin-top: -40px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .info-section {
        margin-bottom: 30px;
        width: 100%;
    }

    .info-grid {
        width: 100%;
        border-spacing: 0;
    }

    .info-grid td {
        vertical-align: top;
        width: 50%;
        padding: 10px;
    }

    .info-grid p {
        margin: 5px 0;
        line-height: 1.5;
    }

    
        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
            
        }
        .evaluation-table th, 
        .evaluation-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .evaluation-table th {
            background-color: #f3f4f6;
        }
        .recommendation {
            margin: 2px 0;
            padding: 5px;
        }
        .signatures-table {
        width: 100%;
        margin-top: 50px;
        border-spacing: 0;
        page-break-inside: avoid;
    }
    
    .signatures-table td {
        width: 50%;
        padding: 15px;
        vertical-align: top;
        text-align: center;
    }
    
    .signature-container {
        position: relative;
        min-height: 100px;
    }
    
    .signature-image {
        position: absolute;
        top: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        opacity: 0.9;
    }
    
    .signature-line {
        border-bottom: 1px solid #000;
        width: 200px;
        margin: 30px auto 5px;
    }
    
    .signature-name {
        margin: 5px 0;
        font-weight: bold;
    }
    
    .signature-title {
        margin: 5px 0;
        color: #666;
    }
        .program-head {
        width: auto;
    }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            border-top: 1px solid #eee;
            padding: 10px;
            font-size: 10px;
            background: white;
        }
        .page-number {
            position: fixed;
            bottom: 30px;
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        .document-header {
    width: 100%;
    padding: 0;               /* Remove padding if not needed */
    margin: 0;                /* Remove margin */
    text-align: center;
    position: relative;
    top: -30px;                   /* Ensure it's at the top */
}

.document-header img {
    display: block;           /* Removes whitespace below image */
    margin: 0 auto;           /* Center the image */
    max-width: 100%;          /* Fit within the page */
    height: auto;
}
    .document-footer {
        width: 100%;
        margin-top: 20px;
        text-align: center;
    }
    .document-footer img {
        max-width: 800px;
        height: auto;
    }
    </style>
</head>
<body>
    

    @if($settings->header_image)
    <div class="document-header">
        <img src="{{ public_path('storage/' . $settings->header_image) }}" alt="">
    </div>
    <div class="header1">
        <h3>On-the-Job-Training Program Performance Evaluation Report</h3>
    </div>
    @else
    <div class="header">
        <strong><p>{{ $settings->school_name ?? 'InternSync' }}</p></strong>
        <strong><p>{{ $settings->school_address ?? 'N/A' }}</p></strong>
        <h3>On-the-Job-Training Program Performance Evaluation Report</h3>
    </div>
@endif


    <div class="info-section">
        <table class="info-grid">
            <tr>
                <td>
                    <p><strong>Student:</strong> {{ $deployment->student->first_name }} {{ $deployment->student->last_name }}</p>
                    <p><strong>Course:</strong> {{ $deployment->student->section->course->course_name }}</p>
                    <p><strong>Company:</strong> {{ $deployment->department->company->company_name }}</p>
                    <p><strong>Address:</strong> {{ $deployment->department->company->address }}</p>
                </td>
                <td>
                    <p><strong>Required Hours:</strong> {{ $deployment->custom_hours }}</p>
                    <p><strong>Completed Hours:</strong> {{ App\Models\Attendance::getTotalApprovedHours($deployment->student_id) }}</p>
                    <p><strong>Training Period:</strong> {{ $deployment->starting_date->format('M d, Y') }} - {{ $deployment->ending_date->format('M d, Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="evaluation-table">
        <thead>
            <tr>
                <th width="60%">Performance Criteria</th>
                <th width="20%">Maximum Rating</th>
                <th width="20%">Rating Given</th>
            </tr>
        </thead>
        <tbody>
            @foreach($criteria as $key => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-center">{{ $maxRatings[$key] }}%</td>
                    <td class="text-center">{{ $ratings[$key] }}%</td>
                </tr>
            @endforeach
            <tr>
                <td><strong>TOTAL</strong></td>
                <td class="text-center"><strong>100%</strong></td>
                <td class="text-center"><strong>{{ $totalScore }}%</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="recommendation">
        <h4>Recommendation:</h4>
        <p>{{ $recommendation }}</p>
    </div>

    <table class="signatures-table">
        <tr>
            <td>
                <div class="signature-container">
                    @if($deployment->supervisor->signature_path)
                        <img 
                            src="{{ public_path('storage/' . $deployment->supervisor->signature_path) }}" 
                            alt=""
                            class="signature-image">
                    @endif
                    <div class="signature-line"></div>
                    <p class="signature-name">
                        {{ $deployment->supervisor->first_name }} {{ $deployment->supervisor->last_name }}
                    </p>
                    <p class="signature-title">Company Supervisor</p>
                </div>
            </td>
            <td>
                <div class="signature-container">
                    @if($deployment->student->section->course->instructorCourses->first()?->instructor->signature_path)
                        

                            <img 
                src="{{ public_path('storage/' . $deployment->student->section->course->instructorCourses->first()?->instructor->signature_path) }}" 
                alt="" 
                class="signature-image"
            >
                    @endif
                    <div class="signature-line"></div>
                    <p class="signature-name">
                        {{ $deployment->student->section->course->instructorCourses->first()?->instructor->first_name }}
                        {{ $deployment->student->section->course->instructorCourses->first()?->instructor->last_name ?? 'N/A' }}
                    </p>
                    <p class="signature-title">
                        Program Head
                    </p>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Performance Evaluation Report
    </div>

    <div class="page-number"></div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->getFont("helvetica");
            $size = 11;
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>