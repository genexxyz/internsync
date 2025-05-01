@component('mail::message')
# Create Supervisor Account

Dear {{ $supervisor_name }},

You have been assigned to supervise interns at {{ $company->company_name }}. Please create your supervisor account using the link below:

@component('mail::panel')
**Company:** {{ $company->company_name }}  
**Department:** {{ $department ?? 'No Department' }}  
**Address:** {{ $address }}
@endcomponent

@component('mail::button', ['url' => route('supervisor.register.reference', ['reference' => $reference_link])])
Create Supervisor Account
@endcomponent

Please note that this link is specific to your email address and can only be used once.

Best regards,<br>
{{ config('app.name') }}
@endcomponent