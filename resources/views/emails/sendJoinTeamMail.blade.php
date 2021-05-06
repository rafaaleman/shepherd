@component('mail::message')

<a href="{{ config('app.url') }}">
    <img src="{{ config('app.url') }}/img/logo-shepherd.png" alt="Shepherd" title="Shepherd">
</a>

<br><br>
Hey!

@component('mail::table')
<img src="{{ config('app.url') . $details['loveone_photo']}}" alt="Shepherd" title="Shepherd" style="float: left; margin-right: 10px; border-radius: 50%; width: 150px;"> 
You have been choosen to become part of the <i>CareTeam</i> of <strong>{{$details['loveone_name']}}</strong> in Shepherd Platform. <br><br>
If you agree, please login and accept the team invitation in the XXXX screen. 
@endcomponent

<br><br>
@component('mail::button', ['url' => $details['url'], 'color' => 'primary'])
Join
@endcomponent

<br>
Thanks,<br>
Shepherd Team
@endcomponent
