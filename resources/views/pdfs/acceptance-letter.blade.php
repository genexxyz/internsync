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
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        .text-xl { font-size: 13pt; }
        .mt-4 { margin-top: 15px; }
        .border-t {
            border-top: 1px dashed #666;
            padding-top: 15px;
            margin-top: 20px;
        }
        
        /* Signature Styles */
        /* Update the signature styles in the <style> section */
.signature-block {
    position: relative;
    margin-top: 20px;
    min-height: 100px;
}

.signature-container {
    position: absolute;
    top: 0; /* Adjusted to move signature up */
    left: -20px;
    width: 150px;
    text-align: center;
}

.program-head-signature {
    max-height: 50px;
    width: auto;
    margin-bottom: -15px;
}

.signature-name {
    margin-top: 30px; /* Adjusted spacing */
    padding-top: 5px;
    width: auto;
}

/* Update supervisor signature styles */
.supervisor-row {
    position: relative;
    margin: 5px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    height: 20px; /* Minimal height */
}

.supervisor-signature-container {
    position: absolute;
    left: 300px; /* Adjust based on your needs */
    top: -25px; /* Move signature up to overlap */
    width: 150px;
    text-align: center;
}

.signature-img {
    max-height: 40px;
    width: auto;
    margin-bottom: 0;
}

.signature-line {
    display: block;
    width: 150px;
    height: 1px;
    background: #000;
    margin-top: 5px;
}

.supervisor-label, .supervisor-name, .signature-label {
    margin: 0;
    padding: 0;
    line-height: 1;
}

.signature-label {
    margin-left: 40px; /* Push to right */
    padding-right: 160px; /* Space for signature */
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

<div class="signature-block">
    <div class="signature-container">
        @if($deployment->student->section->course->instructorCourses->first()?->instructor->signature_path)
            <img 
                src="{{ storage_path('app/public/' . $deployment->student->section->course->instructorCourses->first()?->instructor->signature_path) }}" 
                alt="Program Head Signature" 
                class="program-head-signature"
            >
        @else
            <span class="signature-placeholder">(Program Head Signature)</span>
        @endif
    </div>
    <div class="signature-name">
        {{ $deployment->student->section->course->instructorCourses->first()?->instructor->first_name . ' ' . $deployment->student->section->course->instructorCourses->first()?->instructor->last_name ?? 'N/A' }}<br>
        Program Head - {{ $deployment->student->section->course->course_name }}
    </div>
</div>

    
    <div class="border-t">
        <strong class="mt-4">ACCEPTANCE</strong>
        
        <p>Name of the Company: <u>{{ $deployment->department->company->company_name }}</u></p>
<p>Address: <u>{{ $deployment->department->company->address }}</u></p>
<p>Contact Number: <u>{{ Auth::user()->supervisor->contact }}</u></p>

<div class="supervisor-row">
    <span class="supervisor-label">Supervisor Name:</span>
    <span class="supervisor-name"><u>{{ Auth::user()->supervisor->first_name . ' ' . Auth::user()->supervisor->last_name }}</u></span>
    <span class="signature-label">Signature:</span>
    <div class="supervisor-signature-container">
        @if(Auth::user()->supervisor->signature_path)
            <img 
                src="{{ storage_path('app/public/' . Auth::user()->supervisor->signature_path) }}" 
                alt="Supervisor Signature" 
                class="signature-img"
            >
            <span class="signature-line"></span>
        @else
            <span class="signature-placeholder">(Signature)</span>
        @endif
    </div>
</div>

<p>Position: <u>{{ Auth::user()->supervisor->position }}</u></p>
    </div>
</body>
</html>