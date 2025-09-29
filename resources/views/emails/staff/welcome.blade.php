<x-mail::message>
# Hello {{ $user->name }}

Your staff account has been created.  
Here are your login details:

- **Email:** {{ $user->email }}
- **Password:** {{ $plainPassword }}

<x-mail::button :url="route('password.change')">
Login Here
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
