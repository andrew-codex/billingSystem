@component('mail::message')
# Welcome {{ $consumer->name }}

Your account has been created.

**Email:** {{ $consumer->email }}

**Temporary password:** `{{ $plainPassword }}`

@component('mail::button', ['url' => url('/login')])
Login
@endcomponent

Please change your password after first login.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
