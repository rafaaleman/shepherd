@component('mail::message')

<a href="{{ config('app.url') }}">
    <img src="{{ config('app.url') }}/img/logo-shepherd.png" alt="Shepherd" title="Shepherd">
</a>

<br><br>
@component('mail::table')
<img src="{{ config('app.url') . $email['from_photo']}}" alt="{{$email['from_name']}}" title="{{$email['from_name']}}" style="float: left; margin-right: 10px; border-radius: 50%; width: 100px; height: auto">  
<strong>{{$email['from_name']}}</strong> sent you this message on ShepherdCares:
"<strong>{{$email['message']}}</strong>"
@endcomponent
<br>
Please login into your Shepherd Account to reply.<br>
@component('mail::button', ['url' => route('login'), 'color' => 'primary'])
Login 
@endcomponent
<br><br>
Thanks,<br>
Shepherd Team
@endcomponent
