@component('mail::message')

<a href="{{ config('app.url') }}">
    <img src="{{ config('app.url') }}/img/logo-shepherd.png" alt="Shepherd" title="Shepherd">
</a>

<br><br>
@component('mail::table')
<img src="{{ config('app.url') . $email['loveone_photo']}}" alt="{{$email['loveone_name']}}" title="{{$email['loveone_name']}}" style="float: left; margin-right: 10px; border-radius: 50%; width: 100px; height: auto">  
You now have permission to access <strong>{{$email['permissions_str']}}</strong> on <strong>{{$email['loveone_name']}}</strong> ShepherdCares account.

@endcomponent

<br><br>
@component('mail::button', ['url' => route('login'), 'color' => 'primary'])
Login to view
@endcomponent

<br><br>
Thanks,<br>
Shepherd Team
@endcomponent
