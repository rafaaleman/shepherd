<div class="col-md-12 p-3 d-flex justify-content-end top-bar pr-5 {{ (isset($section)) ? $section : ''}}">
    @if (isset($section))
        @if ( $section == 'careteam')
            <i class="fas fa-users mt-2 mr-3"></i>
        @elseif($section == 'CarePoints')
            <i class="fas fa-calendar-plus mt-2 mr-3"></i>
        @elseif($section == 'MedList')
            <i class="fas fa-prescription-bottle-alt mt-2 mr-3"></i>
        @elseif($section == 'resources')
            <i class="fas fa-globe mt-2 mr-3"></i>
        @elseif($section == 'lockbox')
            <i class="fas fa-file-medical mt-2 mr-3"></i>
        @elseif($section == 'discussions')
            <i class="fas fa-comment-alt mt-2 mr-3"></i>
        @else
            <i class="fas fa-users mt-2 mr-3"></i>
        @endif
        {{ucfirst($section)}}
    @endif
</div>
