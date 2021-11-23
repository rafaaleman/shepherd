@component('mail::message')

<a href="{{ config('app.url') }}">
    <img src="{{ config('app.url') }}/img/logo-shepherd.png" alt="Shepherd" title="Shepherd">
</a>

<br><br>
@component('mail::table')
<img src="{{ config('app.url') . $email['loveone_photo']}}" alt="{{$email['loveone_name']}}" title="{{$email['loveone_name']}}" style="float: left; margin-right: 10px; border-radius: 50%; width: 100px; height: auto">  
<br>You still have missing essential documents in your ShepherdCares account<br><br>
@foreach($email['documents'] as $d)
    @if($d["exist"] == false)
        - {{ $d["name"] }} <br>
    @endif
@endforeach

@endcomponent
<br>
ShepherdCares works best when you have all the necessary documents you need to 
care for {{ $email['loveone_name'] }}. 
<br>Please login and upload your essential documents as soon as you can!<br>
@component('mail::button', ['url' => route('login'), 'color' => 'primary'])
Login 
@endcomponent
<br><br>
Thanks,<br>
The ShepherdCares Team

<br><br><br><br>
<a>Don't remind me again</a>
@endcomponent


