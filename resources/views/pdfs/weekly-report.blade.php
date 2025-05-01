<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>On-the-Job-Training Weekly Journal Form</title>
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
            margin-top: -40px;
        }
        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            color: #2563eb;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            display: flex;
            justify-content: flex-start;
        }
        .info-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .daily-activities {
            width: 100%;
            border-collapse: collapse;
        }
        .daily-activities th, 
        .daily-activities td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .daily-activities th {
            background-color: #f3f4f6;
        }
        .learning-outcomes {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signatures div {
            width: 45%;
        }
        .signatures .signature-line {
            border-bottom: 1px solid #000;
            margin-top: 20px;
            width: 150px;
            padding-left: 30px; 
        }
        .work-list {
        margin: 0;
        padding-left: 20px;
    }
    
    .work-list li {
        margin-bottom: 4px;
    }
    
    .status {
        color: #666;
        font-style: italic;
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
        background-color: white;
        padding: 20px;
    }


    /* Ensure content doesn't overlap with footer */
    main {
        margin-bottom: 100px;
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
        
        <h4>On-the-Job-Training Weekly Journal Form</h4>
        <p>Week {{ $report->week_number }} ({{ $startDate }} - {{ $endDate }})</p>
    </div>
    @else
    <div class="header">
        <div>
                    
            <strong><p>{{ $settings->school_name ?? 'InternSync' }}</p></strong>
            <strong><p>{{ $settings->school_address ?? 'N/A' }}</p></strong>
        </div>
        

        <h4>On-the-Job-Training Weekly Journal Form</h4>
        <p>Week {{ $report->week_number }} ({{ $startDate }} - {{ $endDate }})</p>
    </div>
@endif
    {{-- <div class="header">
        <div>
                    
            <strong><p>{{ $settings->school_name ?? 'InternSync' }}</p></strong>
            <strong><p>{{ $settings->school_address ?? 'N/A' }}</p></strong>
        </div>
        

        <h4>On-the-Job-Training Weekly Journal Form</h4>
        <p>Week {{ $report->week_number }} ({{ $startDate }} - {{ $endDate }})</p>
    </div> --}}
        <div class="info-table">
            <p><strong>Student:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
            @if($deployment && $company)
                <p><strong>Company:</strong> {{ $company->company_name }}</p>
                @if ($deployment->department)
                    <strong>Department:</strong> {{ $deployment->department->department_name }}</p>
                @endif
               
                @if ($deployment->supervisor)
                    <p><strong>Supervisor:</strong> {{ $deployment->supervisor->first_name }} {{ $deployment->supervisor->last_name }}</p>
                
                @endif
            @endif
        </div>
    

        <table class="daily-activities">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Date</th>
                    <th width="50%">Work Description & Tasks</th>
                    <th>Hours</th>
                </tr>
            </thead>
            <tbody>
                @foreach($journals as $journal)
                    <tr>
                        <td>{{ Carbon\Carbon::parse($journal->date)->format('l') }}</td>
                        <td>{{ Carbon\Carbon::parse($journal->date)->format('M d, Y') }}</td>
                        <td>
                            @if($journal->text)
                                <strong>{{ $journal->text }}</strong>
                                @if($journal->tasks->isNotEmpty())
                                    <ul class="work-list">
                                        @foreach($journal->tasks as $task)
                                            <li>{{ $task->description }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            @else
                                @if($journal->tasks->isNotEmpty())
                                    <ul class="work-list">
                                        @foreach($journal->tasks as $task)
                                            <li>{{ $task->description }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500 italic">No entries for this day</p>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($journal->attendance && $journal->attendance->total_hours)
                                @php
                                    list($hours, $minutes) = array_pad(explode(':', $journal->attendance->total_hours), 2, 0);
                                    echo $hours . 'h ' . $minutes . 'm';
                                @endphp
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Total Hours</strong></td>
                    <td>{{ $totalHours }}</td>
                </tr>
            </tfoot>
        </table>

    <div class="learning-outcomes">
        <strong>Learning Outcomes</strong>
        <p>{{ $report->learning_outcomes }}</p>
    </div>

    <div class="signatures">
        <div>
            <h4>Supervisor:</h4>
            <div style="position: relative; width: auto;">
                @if ($report->status == 'approved')
                    <img src="{{ public_path('storage/' . $deployment->supervisor->signature_path) }}" 
                         alt=""
                         style="position: absolute; top: -15px; left: 23%; transform: translateX(-50%); width: 100px; opacity: 0.9;">
                @endif
                <p class="signature-line">{{ $deployment->supervisor->first_name }} {{ $deployment->supervisor->last_name }}</p>
                <p >Signature Over Printed Name</p>
            </div>
        </div>
        <div>
            <h4>Prepared by:</h4>
            <div style="position: relative; width: auto;">
                @if ($report->status == 'approved')
                    
                         @if($deployment->student->section->course->instructorCourses->first()?->instructor->signature_path)
            <img 
                src="{{ public_path('storage/' . $deployment->student->section->course->instructorCourses->first()?->instructor->signature_path) }}" 
                alt="" 
                class="program-head-signature" style="position: absolute; top: -25px; left: 15%; transform: translateX(-50%); width: 100px; opacity: 0.9;">
            
        @else
            <span class="signature-placeholder">(Program Head Signature)</span>
        @endif
                @endif
            </div>
            <div class="program-head" style="width: 300px;">
                {{ $deployment->student->section->course->instructorCourses->first()?->instructor->first_name . ' ' . $deployment->student->section->course->instructorCourses->first()?->instructor->last_name ?? 'N/A' }}<br>
                Program Head - {{ $deployment->student->section->course->course_name }}
            </div>
        </div>
    </div>
    @if($settings->footer_image)
    <div class="document-footer">
        <img src="{{ public_path('storage/' . $settings->footer_image) }}" alt="Footer">
    </div>
    @else
    <div class="footer">
        
        <div class="footer-content">
            On-the-Job Training Daily Journal and Accomplishment Report
        </div>
    </div>
@endif
    
    
    
</body>
</html>