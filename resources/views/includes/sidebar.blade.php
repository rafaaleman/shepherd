<aside class="sidebar shadow-sm">
    
    <a class="item d-block active" href="{{ route('careteam', '**SLUG**')}}">
        <i class="fas fa-users fa-2x"></i> <span>CareTeam</span>
    </a>
    <a class="item d-block" href="">
        <i class="far fa-calendar-plus  fa-2x"></i> <span>CareHub</span>
    </a>
    <a class="item d-block" href="">
        <i class="fas fa-file-medical fa-2x"></i> <span>LockBox</span>
    </a>
    <a class="item d-block" href="">
        <i class="fas fa-prescription-bottle-alt fa-2x"></i> <span>MedList</span>
    </a>
    <a class="item d-block" href="">
        <i class="fas fa-globe"></i> <span>Resources</span>
    </a>
</aside>

@push('scripts')
<script>
$(function(){
    current_loveone = localStorage.getItem('loveone');
    if(current_loveone != null){
        current_loveone = JSON.parse(current_loveone);
        $('.sidebar .item').each( function () { 
            newurl = $(this).attr('href');
            newurl = newurl.replace('**SLUG**', current_loveone.slug);
            $(this).attr('href', newurl)
        });
    }
});
</script>
    
@endpush