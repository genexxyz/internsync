<!-- resources/views/pdf/acceptance_letter.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Acceptance Letter</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 30px;
        }
        .text-center {
            text-align: center;
        }
        .text-justify {
            text-align: justify;
        }
        .font-bold {
            font-weight: bold;
        }
        .text-xl {
            font-size: 13pt;
        }
        .mt-4 {
            margin-top: 15px;
        }
        .mt-5 {
            margin-top: 20px;
        }
        .border-t {
            border-top: 1px dashed #666;
            padding-top: 15px;
            margin-top: 20px;
        }
        .signature-img {
            max-height: 50px;
            max-width: 150px;
            vertical-align: middle;
        }
        .signature-placeholder {
            color: #666;
            font-size: 10pt;
            border-bottom: 1px solid #666;
            display: inline-block;
            width: 150px;
            height: 20px;
            vertical-align: middle;
            text-align: center;
        }
        u {
            text-decoration: underline;
        }
        .supervisor-row {
            width: 100%;
        }
        .supervisor-name {
            display: inline-block;
            width: 60%;
        }
        .supervisor-signature {
            display: inline-block;
            width: 40%;
        }
    </style>
</head>
<body>
    <p class="text-xl text-center font-bold">ACCEPTANCE LETTER</p>
    
    <p class="mt-4"><i>{{ date('F d, Y') }}</i></p>
    
    <p class="mt-4">{{ Auth::user()->supervisor->first_name . ' ' . Auth::user()->supervisor->last_name }}<br>
        {{ $deployment->department->company->company_name }}</p>
    
    <p class="mt-4">Dear Mr/Ms. {{ Auth::user()->supervisor->first_name . ' ' . Auth::user()->supervisor->last_name }},</p>
    
    <p class="mt-4">Greetings!</p>
    
    <p class="mt-4 text-justify">The {{ $settings->school_name }} offers a {{ $deployment->student->section->course->course_name }} program, which requires all graduating students to complete On-the-Job Training as part of their academic curriculum. With the resumption of on-site OJT opportunities, the student is expected to complete {{ $deployment->student->section->course->required_hours }} hours of training, either in person or online, depending on the company's health protocols and work arrangements.</p>
    
    <p class="mt-4 text-justify">In line with this, we respectfully request your good office to accommodate {{ $studentName }}, a dedicated and competent {{ $deployment->student->section->course->course_name }} student, for his/her OJT in your esteemed company. S/he has been carefully selected based on her academic achievements and technical skills. Additionally, s/he has undergone proper orientation to ensure s/he meets the performance standards expected by both the school and the industry.</p>
    
    <p class="mt-4 text-justify">We sincerely appreciate your time and consideration. Thank you in advance for your support, and we look forward to a positive collaboration.</p>
    
    <p class="mt-4 text-justify">Respectfully yours,</p>
    
    <p class="mt-4 text-justify">{{ $deployment->student->section->course->instructorCourses->first()?->instructor->first_name . ' ' . $deployment->student->section->course->instructorCourses->first()?->instructor->last_name ?? 'N/A' }}<br>Program Head - {{ $deployment->student->section->course->course_name }}</p>
    
    <div class="border-t">
        <strong class="mt-4">ACCEPTANCE</strong>
        
        <p>Name of the Company: <u>{{ $deployment->department->company->company_name }}</u></p>
        <p>Address: <u>{{ $deployment->department->company->address }}</u></p>
        <p>Contact Number: <u>{{ Auth::user()->supervisor->contact }}</u></p>
        
        <div class="supervisor-row">
            <div class="supervisor-name">
                Supervisor Name: <u>{{ Auth::user()->supervisor->first_name . ' ' . Auth::user()->supervisor->last_name }}</u>
            </div>
            <div class="supervisor-signature">
                Signature: 
                @if($signatureUrl)
                    <img src="{{ $signatureUrl }}" alt="E-signature" class="signature-img">
                @else
                    <span class="signature-placeholder">(E-signature)</span>
                @endif
            </div>
        </div>
        
        <p>Position of the Contact Person: <u>{{ Auth::user()->supervisor->position }}</u></p>
    </div>
</body>
</html>