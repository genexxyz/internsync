@component('mail::message')
# Welcome to InternSync

Your student account has been created. Here are your login credentials:

@component('mail::panel')
**Email:** {{ $email }}  
**Password:** {{ $password }}
@endcomponent

@component('mail::button', ['url' => route('login')])
Login Now
@endcomponent

Please change your password after your first login for security purposes.

Thanks,<br>
**InternSync Team**
@endcomponent