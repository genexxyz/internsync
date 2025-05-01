@component('mail::message')
# Email Verification Code

Your OTP code for email verification is:

@component('mail::panel')
<div style="text-align: center; font-size: 24px; letter-spacing: 8px; font-weight: bold;">
{{ $otp }}
</div>
@endcomponent

This code will expire in 15 minutes.

Thanks,<br>
{{ config('app.name') }}
@endcomponent