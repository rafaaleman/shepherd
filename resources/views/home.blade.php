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
            <div class="col-md-12 col-lg-8 ">
                
                <div class="row p-5 d-flex justify-content-between flex-wrap">

                    @include('includes.home_careteam')
                    @include('includes.home_carehub')
                    @include('includes.home_lockbox')
                    @include('includes.home_medlist')
                    @include('includes.home_messages')
                    @include('includes.home_resources')
                </div>
            </div>
            
        </div>
    </div>
    @include('includes.create_modal')
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
        carehub_add_url:'',
        lockbox_url:'',
        messages_url:'',
        resources_url:'',
        events_to_day:'',
        active_discussion:'',
        hour_first_event:'',
        medlist_url:'',
        medlist_add_url:'',
        count_medications:0,
        lockBox_count:0, 
        is_admin: false,
        articles: [],
        resources_date:'',
        medlist_date:'',
        lockbox_lastUpdated:'',
        save: false,
        img_url: "{{asset('images/no_photo.jpg')}}",
        permissions: [],
        careteam:[],
        num_m : 0,
        lastMU:'',
            document: {
                id: 0,
                user_id: {{Auth::Id()}},
                lockbox_types_id: '',
                loveones_id: this.loveone_id,
                name: '',
                description:'',
                file:'',                
                status: 1,
                permissions : []
            },
    },
    filters: {
        isImage(file){
                let exts = ['jpg','jpeg','gif','png','svg'];
                let str = "{{asset('images/no_photo.jpg')}}";
                let ext = "txt";
                
                if(file){
                    if(file.name){
                        ext = file.name.split('.').pop();
                    }else{
                        ext = file.split('.').pop();
                    }
                    if(exts.indexOf(ext) >= 0){
                            str = "{{ URL::to('/') }}" + file;
                    }
                    else if(ext == "pdf"){
                        str = "{{asset('images/file_pdf.jpg')}}";
                    }
                    else if(ext == "doc" || ext == "docx"){
                        str = "{{asset('images/file_doc.jpg')}}";
                    }else{
                        str = "{{asset('images/file_other.jpg')}}";
                    }
                }
                return str;
            },
            urlFile(file){                
                 return str = "{{ URL::to('/') }}" + file;
            }
    },
    computed:{ 
    },
    methods: {
        borrarDoc: function(){
            this.permissions= []
            this.img_url= "{{asset('images/no_photo.jpg')}}"
            $('#ffile').html('');
            this.document = {
                id: 0,
                user_id: {{Auth::Id()}},
                lockbox_types_id: '',
                loveones_id: this.loveone_id,
                name: '',
                description:'',
                file:'',                
                status: 1,
                permissions : []
            }
        },
        refreshWidgets: function( loveone_id, current_slug ){
            this.loveone_id = loveone_id;
            this.current_slug = current_slug;
            this.setLoveone(loveone_id);
            this.getMedlist();
            this.getCareteamMembers();
            this.getEvents();
            this.getCountLockBox();
            this.getResources();
            this.getMessages();
            this.getDiscussions();
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
                    this.careteam = response.data.data.members; 
                    var url = '{{ route("careteam", "*SLUG*") }}';
                    this.careteam_url = url.replace('*SLUG*', this.current_slug);
                    this.is_admin = response.data.data.is_admin; 
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
        getEvents: function() {
            
           //  console.log(this.current_slug);  
          //  $('.hub .events-today').hide(); 
          //  $('.loading-carehub').show();        
            const hoy = new Date();

            var url = '{{ route("carehub.getEvents", ["*SLUG*","*DATE*",1]) }}';
                url = url.replace('*SLUG*', this.current_slug);
                url = url.replace('*DATE*', '{{$date->format("Y-m-d")}}');
            axios.get(url).then(response => {               
                 // console.log(response.data);
                
                if(response.data.success){
                    this.events_to_day = response.data.data.events; 

                    var url = '{{ route("carehub", "*SLUG*") }}';
                    var url_add = '{{ route("carehub.event.form.create", "*SLUG*") }}';
                    this.carehub_url = url.replace('*SLUG*', this.current_slug);
                    this.carehub_add_url = url_add.replace('*SLUG*', this.current_slug);

                    this.hour_first_event = response.data.data.time_first_event;
                } else {
                    msg = 'There was an error. Please try again';
                    icon = 'error';
                    swal(msg, "", icon);
                }
                
              //  $('.loading-carehub').hide();
              //  $('.hub .events-today').show();
                
            }).catch( error => {
                
                msg = 'There was an error getting event. Please reload the page';
                swal('Error', msg, 'error');
            });
        },
        getDiscussions: function() {
            
            //  console.log(this.current_slug);  
           //  $('.hub .events-today').hide(); 
           //  $('.loading-carehub').show();        
             const hoy = new Date();
 
             var url = '{{ route("carehub.getDiscussions", ["*SLUG*"]) }}';
                 url = url.replace('*SLUG*', this.current_slug);
             axios.get(url).then(response => {               
                  // console.log(response.data);
                 
                 if(response.data.success){
                     this.active_discussion = response.data.data.discussions; 
 
                     
                 } else {
                     msg = 'There was an error. Please try again';
                     icon = 'error';
                     swal(msg, "", icon);
                 }
                 
               //  $('.loading-carehub').hide();
               //  $('.hub .events-today').show();
                 
             }).catch( error => {
                 
                 msg = 'There was an error getting event. Please reload the page';
                 swal('Error', msg, 'error');
             });
         },

        getMedlist: function() {
            var url = '{{ route("medlist", "*SLUG*") }}';
            var url_add = '{{ route("medlist.form.create", "*SLUG*") }}';
            this.medlist_url = url.replace('*SLUG*', this.current_slug);


            this.medlist_add_url = url_add.replace('*SLUG*', this.current_slug);
            //  console.log(this.current_slug);  
          //  $('.medlist .medlist-today').hide(); 
          //  $('.loading-medlist').show();        
            const hoy = new Date();

            var url = '{{ route("medlist.getMedications", ["*SLUG*","*DATE*"]) }}';
                url = url.replace('*SLUG*', this.current_slug);
                url = url.replace('*DATE*', '{{$date->format("Y-m-d")}}');
            axios.get(url).then(response => {               
                   console.log(response.data.data);
                
                if(response.data.success){
                    
                    this.count_medications = response.data.data.count_medications; 
                    var url = '{{ route("medlist", "*SLUG*") }}';
                    this.medlist_url = url.replace('*SLUG*', this.current_slug);
                    this.medlist_date =  response.data.data.next_dosage;

                } else {
                    msg = 'There was an error. Please try again';
                    icon = 'error';
                    swal(msg, "", icon);
                }
                
            //    $('.loading-medlist').hide();
            //    $('.medlist .medlist-today').show();
                
            }).catch( error => {
                
                msg = 'There was an error getting medlist. Please reload the page';
                swal('Error', msg, 'error');
            });
        },
        
        getCountLockBox: function(){
            var url = '{{ route("lockbox.countDocuments", "*SLUG*") }}';
                url = url.replace('*SLUG*', this.current_slug);
            $('.loading-carehub').show();
            axios.get(url).then(response => {               
                if(response.data.success){
                    console.log(response.data);
                    this.lockBox_count = response.data.data.documents;  
                    this.lockbox_lastUpdated = response.data.data.l_document;
                } else {
                    this.lockBox_count = 0;
                }
                url = '{{ route("lockbox", "*SLUG*") }}';
                this.lockbox_url = url.replace('*SLUG*', this.current_slug);
                $('.loading-carehub').hide();                
            }).catch( error => {
                
                msg = 'There was an error getting lockbox. Please reload the page';
                swal('Error', msg, 'error');
            });
            
        },
        getResources: function(){
            var url = '{{ route("resources", "*SLUG*") }}';
            this.resources_url  = url.replace('*SLUG*', this.current_slug);


            var url = '{{ route("resources.carehub", "*SLUG*") }}';
            this.r_url  = url.replace('*SLUG*', this.current_slug);
            this.resources_date = '';
            var fecha = undefined;
            axios.get(this.r_url).then(function(response){
                home.articles = response.data.topics.articles;

                home.articles.forEach(function(val){
                    if(fecha == undefined){
                        fecha = moment(val.publishedAt);
                    }
                    if(moment(val.publishedAt).isAfter(fecha)){
                        fecha = moment(val.publishedAt);
                    }
                });
                $('.loading-articles').hide();
                if(fecha != undefined){
                    home.resources_date = fecha.fromNow();

                }
                
            });
        },
        getMessages(){
            var url2 = '{{ route("messages", "*SLUG*") }}';
            this.messages_url = url2.replace('*SLUG*', this.current_slug);
            var url = '{{ route("messages.last") }}';
            
            axios.post(url, {loveone_slug: this.current_slug, user_id :{{Auth::Id()}}  }).then(response => {
                 console.log(response.data);
                if(response.data.success){
                    this.num_m =response.data.data.num_message;
                    this.lastMU= response.data.data.last_message;
                } else {
                    msg = 'There was an error. Please try again';
                    icon = 'error';
                    swal(msg, "", icon);
                }
                
            }).catch( error => {
                
                msg = 'There was an error getting messages. Please reload the page';
                swal('Error', msg, 'error');
            });

        },
        //Rene lockbox
        showModal() {
            this.borrarDoc();
            this.document.lockbox_types_id = 8;
            //this.buildPermission();
            $('#createModal').modal('show');
        },
        getDoc(event){
                this.document.file = event.target.files[0];                
                let exts = ['jpg','jpeg','gif','png','svg'];                
                const ext = event.target.files[0].name.split('.').pop();
                if(exts.indexOf(ext) >= 0){
                    this.img_url = URL.createObjectURL(this.document.file);
                }
                else if(ext == "pdf"){
                    this.img_url = "{{asset('images/file_pdf.jpg')}}";
                }
                else if(ext == "doc" || ext == "docx"){
                    this.img_url = "{{asset('images/file_doc.jpg')}}";
                }else{
                    this.img_url = "{{asset('images/file_other.jpg')}}";
                }        
                $('#ffile').html(event.target.files[0].name);
                this.save = true;
        },
        createDocument() {
                const url = "{{route('lockbox.store')}}";
                const formData = new FormData();                
                formData.append('id', this.document.id);
                formData.append('user_id', this.document.user_id);
                formData.append('loveones_id', this.document.loveones_id);
                formData.append('lockbox_types_id', this.document.lockbox_types_id);
                formData.append('name', this.document.name);
                formData.append('description', this.document.description);
                formData.append('file', this.document.file);
                formData.append('status', this.document.status);
                formData.append('permissions', JSON.stringify(this.permissions));

                $('#createModal .btn-submit').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Saving...').attr('disabled', true);
                
                axios.post(url, formData,{ 
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {                        
                    if( response.data.success == true ){
                        msg = 'Your document has been uploaded';
                        icon = 'success';
                    } else {
                        msg = 'There was an error. Please try again';
                        icon = 'error';
                    }                            
                    swal(msg, "", icon);
                    this.borrarDoc();   
                    this.getCountLockBox();
                    $('#createModal .btn-submit').html('Save').attr('disabled', false);
                    $('#createModal').modal('hide');

                }).catch(error => {                    
                    this.errors = error.response.data;
                    console.log(error);
                });
        },
        hideModal(modal) {
            this.borrarDoc();
            $('#'+modal).modal('hide');
        },
        buildPermission(){
                this.permissions = [];
                for(var i = 0, len = this.careteam.length; i < len; i++) {
                    
                    if( this.careteam[i].role === "admin" ){
                        p ={ 'user' : this.careteam[i].id, 'r' : 1 };
                    }else{
                        p ={ 'user' : this.careteam[i].id, 'r' : 0 };
                    }
                    this.careteam[i].permissions = p; 
                    this.permissions.push(p);
                }                
                
            },            
            assignPermission(u){                
                let i = this.permissions.findIndex( item => item.user === u);                
                this.permissions[i].r = (this.permissions[i].r == 1) ? 0 :1;
            },
    },
});


$(function(){
    $(".tabs").click(function(){            
            $(".tabs").removeClass("active");
            $(".tabs h6").removeClass("font-weight-bold");
            $(".tabs h6").addClass("text-muted");
            $(this).children("h6").removeClass("text-muted");
            $(this).children("h6").addClass("font-weight-bold");
            $(this).addClass("active");

            current_fs = $(".active");

            next_fs = $(this).attr('id');
            next_fs = "#" + next_fs + "1";

            $("fieldset").removeClass("show");
            $(next_fs).addClass("show");

            current_fs.animate({}, {
                step: function() {
                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    next_fs.css({
                        'display': 'block'
                    });
                }
            });
        });

    @if (session('err_permisison'))
    
    swal('Error',"{!! session('err_permisison') !!}", 'error');
    @endif

    $('.carousel').carousel({
        interval: false
    });

    $('.carousel-control-prev').click(function(){
        $('.carousel').carousel('prev');
        $active_id = $('.carousel-item.active').data('id');
        $active_slug = $('.carousel-item.active').data('slug');
        home.refreshWidgets($active_id, $active_slug);
    });

    $('.carousel-control-next').click(function(){
        $('.carousel').carousel('next');
        $active_id = $('.carousel-item.active').data('id');
        $active_slug = $('.carousel-item.active').data('slug');
        home.refreshWidgets($active_id, $active_slug);
    });

    $('#homeCarousel .carousel-item').click(function(){
        // $('#homeCarousel .carousel-item .btn').attr('disabled', false).text('Select').removeClass('disabled').removeClass('btn-secondary').addClass('btn-primary');
        // $(this).attr('disabled', true).text('Selected').addClass('disabled').removeClass('btn-primary').addClass('btn-secondary');
        //setLighterBg($(this).data('color'));
    });

    //setCarouselColors();

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
                // if(current_loveone.id == item.getAttribute('data-id')) setLighterBg(color);
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

    
</script>


@endpush
