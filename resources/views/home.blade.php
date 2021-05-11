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
    
    <div class="container-fluid dashboard" >
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
            careteam_url: ''
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
            },
            setLoveone: function(loveone_id) {

                var url = '{{ route("loveone.setLoveone") }}';
                axios.post(url, { id: loveone_id }).then(response => {
                    console.log(response.data);
                    localStorage.removeItem('loveone');
                    localStorage.setItem('loveone', JSON.stringify(response.data.loveone));
                });
            },
            getCareteamMembers: function() {
                
                // console.log('getting members');  
                $('.widget.team .member-img').hide();   
                $('.widget.team .loading').show();           

                var url = '{{ route("careteam.getCareteamMembers") }}';
                axios.post(url, {loveone_slug: this.current_slug}).then(response => {
                    console.log(response.data);
                    
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
        });
    });
</script>
@endpush
