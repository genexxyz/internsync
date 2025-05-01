@component('mail::message')
# Hello {{ $user->name }},

Here is your **One-Time Password (OTP)** for email verification:

@component('mail::panel')
{{ $otp }}
@endcomponent

This OTP will expire in 15 minutes. Please do not share it with anyone.

Thanks,<br>
**InternSync Team**
@endcomponent
