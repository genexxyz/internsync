<html>

<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      width: 50%;
      /* Set to half width */
      height: 100vh;
      /* Full viewport height */
      margin: 0;
      padding: 10px;
      font-size: 11px;
      border: 1px solid royalblue;
      color: royalblue;
      /* Increased for better readability */
    }

    h2 {
      font-size: 14px;
      text-align: center;
      margin: 5px 0;
      text-transform: uppercase;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
      margin: 10px 0;
    }

    th,
    .dtr-table td {
      border: 1px solid royalblue;
      padding: 2px;
      text-align: center;
      font-weight: bold;
    }

    .civil_service_title {
      font-size: 9px;
      margin: 3px 0;
      text-align: left;
      font-style: italic;
    }

    .dtr {
      font-size: 13px;
      font-weight: bold;
      text-align: center;
      margin: 5px 0;
    }

    .circles {
      text-align: center;
      font-size: 12px;
      margin: 3px 0;
    }

    .name {
      text-align: center;
      font-size: 11px;
      margin: 0;
    }

    .details-table {
      width: 100%;
      margin: 15px 0;
      border: none;
    }

    .details-table td {
      border: none;
      padding: 2px;
      font-size: 9px;
      vertical-align: top;
    }

    .details-table .left-cell {
      width: 40%;
      line-height: 1.5;
      text-align: left;
      font-style: italic;
    }

    .details-table .right-cell {
      width: 60%;
      text-align: right;
      line-height: 1.5;
    }

    .month-underline {
      border-bottom: 1px solid royalblue;
      display: inline-block;
      min-width: 100px;
      text-align: center;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .schedule-line {
      border-bottom: 1px solid royalblue;
      display: inline-block;
      width: 100px;
      margin-left: 5px;
      text-align: center;
    }

    .total-row {
      text-align: right;
      font-weight: bold;
    }

    .certification {
      font-size: 9px;
      margin: 15px 0;
      line-height: 1.4;
      font-style: italic;
      text-align: justify;
    }

    .signature-line {
      margin: 0;
      border-bottom: 1px solid royalblue;
      min-width: 70%;
      text-align: center;
      font-weight: bold;
    }

    .signature-label {
      font-size: 9px;
      text-align: center;
      margin: 5px 0 15px;
    }

    .supervisor-line {
      margin: 0;
      border-bottom: 1px solid royalblue;
      min-width: 50%;
      text-align: center;
      font-weight: bold
    }

    .supervisor-label {
      font-size: 9px;
      text-align: center;
      margin: 5px 0;
    }

    table th[rowspan="2"] {
      width: 10%;
    }

    table th[colspan="2"] {
      width: 30%;
    }

    .name-line {
      margin: 0;
      border-bottom: 1px solid royalblue;
      min-width: 70%;
      text-align: center;
      font-weight: bold;
      text-transform: uppercase;
    }

    .verified {
      font-size: 9px;
      margin: 10px 0 40px;
      line-height: 1.4;
      font-style: italic;
      text-align: left;
    }

  </style>
</head>

<body>
  <h2>{{$settings->school_name}}</h2>
  <p class="civil_service_title">Civil Service Form No. 48</p>
  <p class="dtr">DAILY TIME RECORD</p>
  <p class="circles">-----o0o-----</p>

  <div class="name-line">{{$student->last_name ?? ''}} {{$student->suffix . ', ' ?? ', '}}{{$student->first_name ?? ''}}
    {{$student->middle_name ?? ''}}</div>
  <p class="name">(Name)</p>

  <table class="details-table">
    <tr>
      <td class="left-cell">
        For the month of:<br>
        Official hours for arrival<br>
        and departure
      </td>
      <td class="right-cell">
        <span class="month-underline">{{ $month }}</span><br>
        <i>Regular days:</i> <span class="schedule-line"></span><br>
      <i>Saturdays:</i> <span class="schedule-line"></span>
      </td>
    </tr>
  </table>

  <table class="dtr-table">
    <tr>
      <th rowspan="2" style="width:10%">Day</th>
      <th colspan="2" style="width:30%">A.M.</th>
      <th colspan="2" style="width:30%">P.M.</th>
      <th colspan="2" style="width:30%">Total</th>
    </tr>
    <tr>
      <th>Arrival</th>
      <th>Departure</th>
      <th>Arrival</th>
      <th>Departure</th>
      <th>Hours</th>
      <th>Minutes</th>
    </tr>
    @foreach($dtrData as $day => $times)
    <tr>
      <th>{{ $day }}</th>
      <td>{{ $times['am_in'] }}</td>
      <td>{{ $times['am_out'] }}</td>
      <td>{{ $times['pm_in'] }}</td>
      <td>{{ $times['pm_out'] }}</td>
      <td>{{ $times['hours'] }}</td>
      <td>{{ $times['minutes'] }}</td>
    </tr>
  @endforeach
  <tr>
    <th colspan="5" class="total-row">Total</th>
    <td>{{ $totalHours }}</td>
    <td>{{ $totalMinutes }}</td>
</tr>
  </table>

  <p class="certification">I certify on my honor that the above is a true and correct report of the hours of work
    performed, record of which was made daily at the time of arrival and departure from office.</p>

  <div class="signature-line">{{$student->first_name ?? ''}} {{$student->middle_name ?? ''}} {{$student->last_name ?? ''}} {{$student->suffix ?? ''}}</div>
  <p class="signature-label"><i>(Name and Signature)</i></p>


    <p class="verified">VERIFIED as to the prescribed office hours:</p>


  <div class="supervisor-line">{{$student->deployment->supervisor->first_name ?? ''}} {{$student->deployment->supervisor->middle_name ?? ''}} {{$student->deployment->supervisor->last_name ?? ''}} {{$student->deployment->supervisor->suffix ?? ''}}</div>
  <p class="supervisor-label"><i>Supervisor</i></p>
</body>

</html>