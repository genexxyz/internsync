<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Acceptance Letter</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 40px;
            line-height: 1.6;
            color: #000;
            font-size: 12pt;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signature {
            margin-top: 60px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 250px;
            margin-top: 40px;
            margin-bottom: 5px;
        }
        .signature-image {
            height: 80px;
            margin-bottom: 5px;
        }
        p {
            margin-bottom: 10px;
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SUPERVISOR ACCEPTANCE LETTER</h1>
        <p>InternSync OJT Supervision Program</p>
    </div>

    <p><i>{{ $date }}</i></p>

    <p>The Program Head<br>Pangasinan State University</p>

    <p>Dear Sir/Madam,</p>

    <p>
        I, <i>{{ $name }}</i>, in my capacity as <i>{{ $position }}</i> at <i>{{ $company_name }}</i>
        @if($department_name) , <i>{{ $department_name }} Department</i>, @endif 
        hereby formally accept the responsibility to serve as an internship supervisor for students from Pangasinan State University.
    </p>
    
    <p>
        I understand that my duties include providing guidance, monitoring progress, evaluating performance, and ensuring 
        that students receive meaningful work experiences aligned with their academic program. I commit to maintaining 
        regular communication with the university regarding the students' progress and any concerns that may arise.
    </p>
    
    <p>
        @if($selected_students_count > 0)
            I have selected {{ $selected_students_count }} student(s) to supervise during their internship program
            and will provide them with appropriate professional guidance throughout their training period.
        @else
            I acknowledge that I currently do not have any students assigned, but understand that 
            students may be assigned to me in the future, at which point I will provide them with appropriate professional guidance.
        @endif
    </p>
    
    <p>
        Our company will ensure that all students under my supervision will have access to necessary resources and opportunities 
        to fulfill their required training hours in a safe and supportive environment.
    </p>
    
    <p>
        For any inquiries, I can be reached at:<br>
        Contact Number: <i>{{ $contact }}</i><br>
        Address: <i>{{ $address }}</i>
    </p>
    
    <div class="signature">
        <p>Respectfully,</p>
        
        @if(!empty($signature_path))
            <div class="signature-image">
                <img src="{{ public_path(str_replace('/storage', 'storage/app/public', $signature_path)) }}" height="80">
            </div>
        @else
            <div class="signature-line"></div>
        @endif
        
        <p><i>{{ $name }}</i><br>
        {{ $position }}<br>
        {{ $company_name }}</p>
    </div>
</body>
</html>