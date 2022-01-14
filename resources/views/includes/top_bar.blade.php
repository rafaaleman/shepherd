<div class="col-md-12 p-3 d-flex justify-content-end top-bar">
    <div>
        <div class="name">Loved one Name</div>
        Loved one
    </div>
    <div class="photo" style="" alt=""></div>

</div>

@push('scripts')
<script>
$(function(){

    loveone = localStorage.getItem('loveone');
    if(loveone != null){
        loveone = JSON.parse(loveone);
        // console.log(loveone.firstname);
        $('.top-bar .name').text(loveone.firstname + ' ' + loveone.lastname);
        $('.top-bar .photo').css('background-image', 'url('+loveone.photo+')');
        $('.top-bar').css('background-color', loveone.color);
    }

    current_loveone = localStorage.getItem('loveone');
    if(current_loveone != null){
        current_loveone = JSON.parse(current_loveone);
        $('.menu-link').each( function () { 
            newurl = $(this).attr('href');
            // console.log(newurl);
            newurl = newurl.replace('**SLUG**', current_loveone.slug);
            $(this).attr('href', newurl)
        });
    } else {
        //$('.notificationsLnk').hide();
    }
});

setTimeout(() => {
    current_loveone = localStorage.getItem('loveone');
    if(current_loveone == null){
        $('.notificationsLnk').hide();
    }
}, 2000);
</script>
@endpush