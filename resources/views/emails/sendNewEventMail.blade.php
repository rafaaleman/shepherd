@component('mail::message')

<a href="{{ config('app.url') }}">
    <img src="{{ config('app.url') }}/img/logo-shepherd.png" alt="Shepherd" title="Shepherd">
</a>

<br><br>
@component('mail::table')

Event called "{{$event->name}}" with location at "{{$event->location}}" on "{{$event->date}}" at "{{$event->time}}"

@endcomponent
<br><br>
@component('mail::button', ['url' => $url, 'color' => 'primary'])
View Event
@endcomponent

<br>
Thanks,<br>
Shepherd Team
@endcomponent
