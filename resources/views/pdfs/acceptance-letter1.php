<?php

// Define variables for dynamic content
$date = date("F d, Y");
$student_name = "<i>" . $student->first_name . ' ' . $student->last_name . "</i>";
$program = "<i>" . $student->section->course->course_name . "</i>";
$company_name = "<i>" . $letter->company_name . "</i>";
$recipient_name = "<i>" . $letter->name . "</i>";
$contact = "<i>" . $letter->contact . "</i>";
$address = "<i>" . $letter->address . "</i>";
$position = "<i>" . $letter->position . "</i>";
$school_name = "<i>" . ($settings->school_name ?? 'InternSync') . "</i>";
$program_head = "<i>" . $programHead . "</i>";
$hours = "<i>" . $requiredHours . "</i>";

// Generate the letter
$letter = <<<EOD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceptance Letter</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 40px;
        }
        .signature {
            margin-top: 30px;
        }
        .acceptance-section {
            margin-top: 50px;
            border-top: 1px dashed #000;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <p><i>$date</i></p>

    <p>$recipient_name<br>
    $company_name</p>

    <p>Dear Mr/Ms. $recipient_name,</p>

    <p>Greetings!</p>

    <p>The $school_name offers a $program program, which requires all graduating students to complete On-the-Job Training as part of their academic curriculum. With the resumption of on-site OJT opportunities, students are expected to complete $hours hours of training, either in person or online, depending on the company’s health protocols and work arrangements.</p>
    
    <p>In line with this, we respectfully request your good office to accommodate $student_name, a dedicated and competent $program student, for his/her OJT in your esteemed company. S/he has been carefully selected based on her academic achievements and technical skills. Additionally, s/he has undergone proper orientation to ensure s/he meets the performance standards expected by both the school and the industry.</p>
    
    <p>We sincerely appreciate your time and consideration. Thank you in advance for your support, and we look forward to a positive collaboration.</p>

    <p>Respectfully yours,</p>

    <p class="signature">$program_head<br>Program Head - $program</p>

    <div class="acceptance-section">
        <strong>ACCEPTANCE</strong>
        <p>Name of the Company: <u>$company_name</u></p>
        <p>Address: <u>$address</u></p>
        <p>Telephone Number: <u>$contact</u></p>
        <p>Contact Person’s Name: <u>$recipient_name</u> Signature: _______________</p>
        <p>Position of the Contact Person: <u>$position</u></p>
    </div>

</body>
</html>
EOD;

echo $letter;

?>
