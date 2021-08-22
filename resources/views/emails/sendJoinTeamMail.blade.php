@component('mail::message')

<a href="{{ config('app.url') }}">
    <img src="{{ config('app.url') }}/img/logo-shepherd.png" alt="Shepherd" title="Shepherd">
</a>

<br><br>
@component('mail::table')
<img src="{{ config('app.url') . $details['loveone_photo']}}" alt="Shepherd" title="Shepherd" style="float: left; margin-right: 10px; border-radius: 50%; width: 150px;">  
You have been invited to become a member of the CareTeam for {{$details['loveone_name']}} in the ShepherdCares platform.

ShepherdCares enables families to more easily manage the care and affairs of their loved ones who need assistance. You would be a valuable addition to this team.

If you would like to join this CareTeam, please click on the button below to register.

@endcomponent

<br><br>
@component('mail::button', ['url' => $details['url'], 'color' => 'primary'])
Register
@endcomponent

<br>
Thanks,<br>
Shepherd Team
@endcomponent
