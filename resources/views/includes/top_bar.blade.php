<div class="col-md-12 p-3 d-flex justify-content-end top-bar pr-5 {{ (isset($section)) ? $section : ''}}">
    @if (isset($section))
        @if ( $section == 'careteam')
            <i class="fas fa-users mt-2 mr-3"></i>
        @else
            <i class="fas fa-users mt-2 mr-3"></i>
        @endif
        {{ucfirst($section)}}
    @endif
</div>
