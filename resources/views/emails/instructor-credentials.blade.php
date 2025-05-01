@component('mail::message')
# Welcome to InternSync

Your instructor account has been created. Here are your login credentials:

@component('mail::panel')
**Email:** {{ $email }}  
**Password:** {{ $password }}
@endcomponent

@component('mail::button', ['url' => route('login')])
Login
@endcomponent

Please change your password after logging in for the first time.
If you have any questions or need assistance, feel free to reach out to us.


Thanks,<br>
**InternSync Team**
@endcomponent