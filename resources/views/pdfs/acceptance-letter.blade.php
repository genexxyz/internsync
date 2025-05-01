<!-- resources/views/pdf/acceptance_letter.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Acceptance Letter</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 0 30px;
            padding: 0;
        }

        .text-center {
            text-align: center;
            margin-top: -30px;
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
            top: 0;
            /* Adjusted to move signature up */
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
            margin-top: 30px;
            /* Adjusted spacing */
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
            height: 20px;
            /* Minimal height */
        }

        .supervisor-signature-container {
            position: absolute;
            left: 300px;
            /* Adjust based on your needs */
            top: -25px;
            /* Move signature up to overlap */
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

        .supervisor-label,
        .supervisor-name,
        .signature-label {
            margin: 0;
            padding: 0;
            line-height: 1;
        }

        .signature-label {
            margin-left: 40px;
            /* Push to right */
            padding-right: 160px;
            /* Space for signature */
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
@endif
    <p class="text-xl text-center font-bold">ACCEPTANCE LETTER</p>

    <p class="mt-4"><i>{{ date('F d, Y') }}</i></p>

    <p class="mt-4">{{ $supervisorName }}<br>
        {{ $deployment->department->company->company_name }}</p>

    <p class="mt-4">Dear Mr/Ms. {{ $supervisorName }},</p>

    <p class="mt-4">Greetings!</p>

    {{-- <p class="mt-4 text-justify">The {{ $settings->school_name }} offers a {{
        $deployment->student->section->course->course_name }} program, which requires all graduating students to
        complete On-the-Job Training as part of their academic curriculum. With the resumption of on-site OJT
        opportunities, the student is expected to complete {{ $deployment->student->section->course->required_hours }}
        hours of training, either in person or online, depending on the company's health protocols and work
        arrangements.</p>

    <p class="mt-4 text-justify">In line with this, we respectfully request your good office to accommodate {{
        $studentName }}, a dedicated and competent {{ $deployment->student->section->course->course_name }} student, for
        his/her OJT in your esteemed company. S/he has been carefully selected based on her academic achievements and
        technical skills. Additionally, s/he has undergone proper orientation to ensure s/he meets the performance
        standards expected by both the school and the industry.</p>

    <p class="mt-4 text-justify">We sincerely appreciate your time and consideration. Thank you in advance for your
        support, and we look forward to a positive collaboration.</p> --}}

    {!! nl2br(e($content)) !!}



    <p class="mt-4 text-justify">Respectfully yours,</p>

    <div class="signature-block">
        <div class="signature-container">
            @if($deployment->student->section->course->instructorCourses->first()?->instructor->signature_path)
                <img src="{{ public_path('storage/' . $deployment->student->section->course->instructorCourses->first()?->instructor->signature_path) }}"
                    alt="" class="program-head-signature">
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
        <p>Contact Number: <u>{{ $deployment->supervisor->contact }}</u></p>

        <div class="supervisor-row">
            <span class="supervisor-label">Supervisor Name:</span>
            <span class="supervisor-name"><u>{{ $supervisorName }}</u></span>
            <span class="signature-label">Signature:</span>
            <div class="supervisor-signature-container">
                @if($deployment->supervisor->signature_path)
                    <img src="{{ public_path('storage/' . $deployment->supervisor->signature_path) }}"
                        alt="" class="signature-img">
                    <span class="signature-line"></span>
                @else
                    <span class="signature-placeholder">(Signature)</span>
                @endif
            </div>
        </div>

        <p>Position: <u>{{ $deployment->supervisor->position }}</u></p>
    </div>
    @if($settings->footer_image)
    <div class="document-footer">
        <img src="{{ public_path('storage/' . $settings->footer_image) }}" alt="Footer">
    </div>
@endif
</body>

</html>