@component('mail::message')
# Verify Your Email Address

Hello {{ $user->name }},

Please click the button below to verify your email address:

@component('mail::button', ['url' => $verificationUrl])
Verify Email
@endcomponent

If you did not create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
If you're having trouble clicking the "Verify Email" button, copy and paste the URL below into your web browser:  
[{{ $verificationUrl }}]({{ $verificationUrl }})
@endcomponent
@endcomponent