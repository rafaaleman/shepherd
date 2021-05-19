@extends('layouts.app_simple')

@section('content')
<div id="home">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 dashboard">

                @include('includes.home_carousel')
                
                
            </div>
            
        </div>
    </div>
    
    <div class="container-fluid dashboard widgets-container">
        <div class="row justify-content-center">
            <div class="col-md-8 ">
                
                <div class="row p-5 d-flex justify-content-between">

                    @include('includes.home_careteam')
                    @include('includes.home_carehub')
                    @include('includes.home_lockbox')
                    @include('includes.home_medlist')

                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>

    .top-navigation{
        margin-bottom: 0 !important;
    }
    .top-bar{
        display: none !important;
    }

    main.py-4{
        padding-top: 0 !important;
    }
</style>
@endpush

@push('scripts')
<script src="/js/color-thief.umd.js"></script>

<script>

const home = new Vue ({
    el: '#home',
    created: function() {
        console.log('home');
        
        loveone = localStorage.getItem('loveone');
        if(loveone != null){
            loveone = JSON.parse(loveone);
            l_id   = loveone.id;
            l_slug = loveone.slug;
        } else {
            l_id   = '{{ $loveones[0]->id }}';
            l_slug = '{{ $loveones[0]->slug }}';
        }
        this.refreshWidgets(l_id, l_slug);
        $('#homeCarousel .carousel-item.loveone-' + l_id + ' .btn').attr('disabled', true).text('Selected').addClass('disabled').removeClass('btn-primary').addClass('btn-secondary');
    },
    data: {
        loveone_id : '',
        current_slug : '',
        current_members: '',
        careteam_url: '',
        carehub_url:'',
        events_to_day:'',
        hour_first_event:''
    },
    filters: {
    },
    computed:{ 
    },
    methods: {
        refreshWidgets: function( loveone_id, current_slug ){
            this.loveone_id = loveone_id;
            this.current_slug = current_slug;
            this.setLoveone(loveone_id);
            this.getCareteamMembers();
           this.getEvents();
        },
        setLoveone: function(loveone_id) {

            var url = '{{ route("loveone.setLoveone") }}';
            axios.post(url, { id: loveone_id }).then(response => {
                // console.log(response.data);
                localStorage.removeItem('loveone');
                current_loveone_ = response.data.loveone;
                current_loveone_.color = $('.carousel-item.loveone-' + current_loveone_.id).attr('data-color');
                localStorage.setItem('loveone', JSON.stringify(current_loveone_));
            });
        },
        getCareteamMembers: function() {
            
            // console.log('getting members');  
            $('.widget.team .member-img').hide();   
            $('.widget.team .loading').show();           

            var url = '{{ route("careteam.getCareteamMembers") }}';
            axios.post(url, {loveone_slug: this.current_slug}).then(response => {
                // console.log(response.data);
                
                if(response.data.success){
                    this.current_members = response.data.data.members;
                    this.members = response.data.data.members; 
                    var url = '{{ route("careteam", "*SLUG*") }}';
                    this.careteam_url = url.replace('*SLUG*', this.current_slug);
                } else {
                    msg = 'There was an error. Please try again';
                    icon = 'error';
                    swal(msg, "", icon);
                }
                
                $('.widget.team .loading').hide();
                $('.widget.team .member-img').show();
                
            }).catch( error => {
                
                msg = 'There was an error getting careteam members. Please reload the page';
                swal('Error', msg, 'error');
            });
        },getEvents: function() {
            
           //  console.log(this.current_slug);  
         /*   $('.widget.team .member-img').hide();  */    
            $('loading-carehub').show();        
            const hoy = new Date();

            var url = '{{ route("carehub.getEvents", ["*SLUG*","*DATE*",1]) }}';
                url = url.replace('*SLUG*', this.current_slug);
                console.log(formatoFecha(hoy, 'yy-mm-dd'));
                url = url.replace('*DATE*', formatoFecha(hoy, 'yy-mm-dd'));
            axios.get(url).then(response => {               
                 // console.log(response.data);
                
                if(response.data.success){
                    this.events_to_day = response.data.data.events; 
                    var url = '{{ route("carehub", "*SLUG*") }}';
                    this.carehub_url = url.replace('*SLUG*', this.current_slug);
                    this.hour_first_event = response.data.data.time_first_event;
                } else {
                    msg = 'There was an error. Please try again';
                    icon = 'error';
                    swal(msg, "", icon);
                }
                
                $('.loading-carehub').hide();
               /* $('.widget.team .member-img').show();*/
                
            }).catch( error => {
                
                msg = 'There was an error getting careteam members. Please reload the page';
                swal('Error', msg, 'error');
            });
        },
    },
});


$(function(){
    

    $('.carousel').carousel({
        interval: false
    });

    $('.carousel-control-prev').click(function(){
        $('.carousel').carousel('prev');
    });

    $('.carousel-control-next').click(function(){
        $('.carousel').carousel('next');
    });

    $('#homeCarousel .carousel-item .btn').click(function(){
        $('#homeCarousel .carousel-item .btn').attr('disabled', false).text('Select').removeClass('disabled').removeClass('btn-secondary').addClass('btn-primary');
        $(this).attr('disabled', true).text('Selected').addClass('disabled').removeClass('btn-primary').addClass('btn-secondary');
        setLighterBg($(this).parents('.carousel-item').data('color'));
    });

    setCarouselColors();

    function setCarouselColors(){

        var carousel_items = document.querySelectorAll('#homeCarousel .carousel-item');

        carousel_items.forEach(function(item) {
            const colorThief = new ColorThief();
            // const photo = $(this).find('.loveone-photo');
            const photo = item.querySelector('.loveone-photo');
            // console.log(photo);

            // Make sure image is finished loading
            if (photo.complete) {
                color = colorThief.getColor(photo);
            } else {
                photo.addEventListener('load', function() {
                    color = colorThief.getColor(photo);
                });
            }
            color = rgbToHex(color[0], color[1], color[2]);
            // console.log(color, item);
            item.style.backgroundColor = color;
            item.setAttribute('data-color', color);

            current_loveone = localStorage.getItem('loveone');
            if(current_loveone != null){
                current_loveone = JSON.parse(current_loveone);
                if(current_loveone.id == item.getAttribute('data-id')) setLighterBg(color);
            }
        });
    }

    function setLighterBg(color){
        color2 = shadeColor(color, 30);
        document.querySelector('.widgets-container').style.background = 'linear-gradient(180deg, '+color2+' 10%, #f8fafc 70%)';
    }

    function rgbToHex(r, g, b) {
        return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
    }
    function componentToHex(c) {
        var hex = c.toString(16);
        return hex.length == 1 ? "0" + hex : hex;
    }

    function shadeColor(color, percent) {

        var R = parseInt(color.substring(1,3),16);
        var G = parseInt(color.substring(3,5),16);
        var B = parseInt(color.substring(5,7),16);

        R = parseInt(R * (100 + percent) / 100);
        G = parseInt(G * (100 + percent) / 100);
        B = parseInt(B * (100 + percent) / 100);

        R = (R<255)?R:255;  
        G = (G<255)?G:255;  
        B = (B<255)?B:255;  

        var RR = ((R.toString(16).length==1)?"0"+R.toString(16):R.toString(16));
        var GG = ((G.toString(16).length==1)?"0"+G.toString(16):G.toString(16));
        var BB = ((B.toString(16).length==1)?"0"+B.toString(16):B.toString(16));

        return "#"+RR+GG+BB;
    }
});

function formatoFecha(fecha, formato) {
    const map = {
        dd: fecha.getDate(),
        mm: fecha.getMonth() + 1,
        yy: fecha.getFullYear().toString(),
        yyyy: fecha.getFullYear()
    }

    return formato.replace(/dd|mm|yy|yyyy/gi, matched => map[matched])
}
</script>


@endpush
