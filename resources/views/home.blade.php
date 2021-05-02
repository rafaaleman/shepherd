@extends('layouts.app')

@section('content')
<div class="container" id="home">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card dashboard">
                <div class="card-header">Your loveones</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @include('includes.home_carousel')
                </div>

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

@push('scripts')
<script>

    const home = new Vue ({
        el: '#home',
        created: function() {
            console.log('home');
            this.refreshWidgets({{$loveones[0]->id}}, '{{$loveones[0]->slug}}');
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
                this.getCareteamMembers();
            },
            getCareteamMembers: function() {
                
                // console.log('getting members');  
                $('.widget.team .member-img').hide();   
                $('.widget.team .loading').show();           

                var url = '{{ route("careteam.getCareteamMembers", "*SLUG*") }}';
                url = url.replace('*SLUG*', this.current_slug);
                axios.get(url).then(response => {
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
    
</script>
@endpush
