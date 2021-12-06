@component('mail::message')

<a href="{{ config('app.url') }}">
    <img src="{{ config('app.url') }}/img/logo-shepherd.png" alt="Shepherd" title="Shepherd">
</a>

<br><br>
@component('mail::table')
You have been invited to create your ShepherdCares account as part of your purchase of the HolaGuard™ Telemed Bundle.
<br><br>
ShepherdCares can help you organize and help take care of anyone in your life with special needs such as an elderly parent, an autistic child, or anyone that may be developmentally disabled.
<br><br>
Your account info<br>
Username: {{$details['email']}} <br>
Password: {{$details['pwd']}}

@endcomponent




<br><br>
@component('mail::button', ['url' => $details['url'], 'color' => 'primary'])
Join ShepherdCares
@endcomponent

<br>
Thanks,<br>
Shepherd Team
@endcomponent
