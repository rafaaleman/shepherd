<aside class="sidebar shadow-sm d-none d-md-block">
    
    @include('includes.common_menu')
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