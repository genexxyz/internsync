<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>On-the-Job-Training Weekly Journal Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
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
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
                    
            <strong><p>{{ $settings->school_name ?? 'InternSync' }}</p></strong>
            <strong><p>{{ $settings->school_address ?? 'N/A' }}</p></strong>
        </div>
        

        <h4>On-the-Job-Training Weekly Journal Form</h4>
        <p>Week {{ $report->week_number }} ({{ $startDate }} - {{ $endDate }})</p>
    </div>
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
                <th>Work Description</th>
                <th>Remarks</th>
                <th>Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach($journals as $journal)
                <tr>
                    <td>{{ Carbon\Carbon::parse($journal->date)->format('l') }}</td>
                    <td>{{ Carbon\Carbon::parse($journal->date)->format('M d, Y') }}</td>
                    <td>{{ $journal->text }}</td>
                    <td>
                        @if($journal->attendance && $journal->remarks)
                            {{ ucfirst($journal->remarks) }}
                        @else
                            N/A
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
                <td colspan="4"><strong>Total Hours</strong></td>
                <td>{{ $totalHours }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="learning-outcomes">
        <strong>Learning Outcomes & Accomplishments</strong>
        <p>{{ $report->learning_outcomes }}</p>
    </div>

    <div class="signatures">
        <div>
            <h4>Supervisor:</h4>
            <div style="position: relative; width: auto;">
                @if ($report->status == 'approved')
                    <img src="{{ public_path('storage/signatures/signature.png') }}" 
                         alt="signature"
                         style="position: absolute; top: -20px; left: 23%; transform: translateX(-50%); width: 170px; opacity: 0.9;">
                @endif
                <p><u>_____{{ $deployment->supervisor->first_name }} {{ $deployment->supervisor->last_name }}_____</u></p>
                <p>Signature Over Printed Name</p>
            </div>
        </div>
        <div>
            <h4>Prepared by:</h4>
            <div class="signature-line"></div>
            <p>Program Head</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px; font-size: 10px;">
        <p>On-the-Job Training Daily Journal and Accomplishment Report</p>
    </div>
</body>
</html>