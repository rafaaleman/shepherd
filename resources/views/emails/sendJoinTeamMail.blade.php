@component('mail::message')

<a href="{{ config('app.url') }}">
    <img src="{{ config('app.url') }}/img/logo-shepherd.png" alt="Shepherd" title="Shepherd">
</a>

<br><br>
Hey!

@component('mail::table')
<img src="{{ config('app.url') . $details['loveone_photo']}}" alt="Shepherd" title="Shepherd" style="float: left; margin-right: 10px; border-radius: 50%; width: 150px;"> 
You have been invited by {{$details['leader']}} to join the <i>CareTeam</i> of <strong>{{$details['loveone_name']}}</strong> in the Shepherd caregiving platform on your phone, tablet or computer. <br><br>

If you would like to accept, please click on the "<strong>Register</strong>" button below. 
@endcomponent

<br><br>
@component('mail::button', ['url' => $details['url'], 'color' => 'primary'])
Register
@endcomponent

<br>
Thanks,<br>
Shepherd Team
@endcomponent
