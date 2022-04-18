@component('mail::message')

<a href="{{ config('app.url') }}">
    <img src="{{ config('app.url') }}/img/logo-shepherd.png" alt="Shepherd" title="Shepherd">
</a>

<br><br>
@component('mail::table')
<br>You have been new document!<br><br>
@endcomponent
<br>
<br>Please login to see!<br>
@component('mail::button', ['url' => route('login'), 'color' => 'primary'])
Login 
@endcomponent
<br><br>
Thanks,<br>
The ShepherdCares Team

<br><br><br><br>
<a>Don't remind me again</a>
@endcomponent


