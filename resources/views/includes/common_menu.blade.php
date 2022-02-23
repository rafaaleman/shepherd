<a class="menu-link item d-block d-md-none" href="{{ route('home') }}">
    <i class="fas fa-users fa-2x"></i> <span>CareHub</span>
</a>
<a class="menu-link item d-block careteam {{(Request::is('careteam/*') ) ? 'active' : ''}}" href="{{ route('careteam', '**SLUG**')}}">
    <i class="fas fa-users fa-2x"></i> <span>CareTeam</span>
</a>
<a class="menu-link item d-block hub {{(Request::is('carehub/*') ) ? 'active' : ''}} " href="{{ route('carehub', '**SLUG**')}}">
    <i class="far fa-calendar-plus  fa-2x"></i> <span>CarePoints</span>
</a>
<a class="menu-link item d-block lockbox {{(Request::is('lockbox/*') ) ? 'active' : ''}}" href="{{ route('lockbox', '**SLUG**')}}">
    <i class="fas fa-file-medical fa-2x"></i> <span>LockBox</span>
</a>
<a class="menu-link item d-block medlist {{(Request::is('medlist/*') ) ? 'active' : ''}}" href="{{ route('medlist', '**SLUG**')}}">
    <i class="fas fa-prescription-bottle-alt fa-2x"></i> <span>MedList</span>
</a>
<a class="menu-link item d-block resources {{(Request::is('resources/*') ) ? 'active' : ''}}" href="{{ route('resources', '**SLUG**')}}">
    <i class="fas fa-globe"></i> <span>Resources</span>
</a>
<a class="menu-link item d-block {{(Request::is('messages/*') ) ? 'active' : ''}}" href="{{ route('messages', '**SLUG**')}}">
    <i class="far fa-comment-alt"></i> <span>Messages</span>
</a>
