@extends('layouts.app_simple')

@section('content')
<div id="home">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12 dashboard">

                <div id="homeCarousel" class="carousel slide">
                    <ol class="carousel-indicators">
                        @foreach ($loveones as $lo)
                            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $loop->index }}" class="{{ ($loop->first) ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>

                    <div class="carousel-inner">
                        @foreach ($loveones as $loveone)
                            <div class="carousel-item {{ ($loop->first) ? 'active' : '' }} loveone-{{ $loveone->id }} slide-{{ $loop->index }}" data-id="{{ $loveone->id }}"  data-slug="{{ $loveone->slug }}" data-info="{{json_encode(collect($loveone)->except(['messages', 'resources', 'medlist', 'lockbox', 'discussions']))}}">
                                <div class="carousel-item__container">
                                    <div class="text-center">
                                        <div style="background-image: url('{{ (!empty($loveone->photo) && $loveone->photo != null ) ? asset($loveone->photo) : asset('/img/no-avatar.png')}}')" class="loveone-photo mx-auto"></div>

                                        <div class="carousel__caption">
                                            <h5 class="mb-3">{{ strtoupper($loveone->firstname) }} {{ strtoupper($loveone->lastname) }}</h5>
                                            {{-- <p>{{ $loveone->relationshipName }}</p> --}}
                        
                                            @if ($loveone->careteam->role == 'admin')
                                                <a href="/loveone/{{$loveone->slug}}" class="mt-3"><i class="far fa-edit"></i> Edit Profile</a>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="container mt-5">
                                        <div class="row">

                                            

                                            {{-- Carepoints --}}
                                            <div class="card widget hub shadow-sm mb-3 mr-lg-3">
                                                <div class="card-body">

                                                    <a href="{{ route('carehub', [$loveone->slug] )}}" class="hub">

                                                        <span class="float-right " tabindex="0" data-toggle="tooltip" title="Assign tasks to CareTeam members. Add notes. Schedule appointments and events on the calendar.">
                                                            <i class="fas fa-info-circle text-black-50"></i>
                                                        </span>

                                                        <h5 class="card-title mb-5">
                                                            <i class="far fa-calendar-plus fa-2x hub"></i> CarePoints
                                                        </h5>
                                                        <div class="card-text events-today">
                                                            <div class="card__count">
                                                                
                                                                <span>{{ count($loveone->carepoints->data->events) }}</span> Task(s) for today <br>
                                                                @if ($loveone->carepoints->data->time_first_event)
                                                                    <i class="gray">Task Name at {{$loveone->carepoints->data->time_first_event}}</i>
                                                                @else
                                                                    <i class="gray">No tasks</i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </a>
                                                    {{-- @if ($loveone->careteam->role == 'admin') --}}
                                                        <a href="{{ route('carehub', [$loveone->slug] )}}" class="btn btn-primary btn-sm">View CarePoints</a>
                                                    {{-- @endif --}}
                                                </div>                                           
                                            </div>

                                            {{-- Lockbox --}}
                                            <div class="card widget lockbox shadow-sm mb-3 mr-lg-3">
                                                <div class="card-body">

                                                    <a href="{{ route('lockbox.index', [$loveone->slug] )}}" class="hub">

                                                        <span class="float-right " tabindex="0" data-toggle="tooltip" title="Upload and store important documents needed in the care of your loved one.">
                                                            <i class="fas fa-info-circle text-black-50"></i>
                                                        </span>

                                                        <h5 class="card-title"><i class="fas fa-file-medical fa-2x"></i> LockBox</h5>
                                                        <div class="card-text">
                                                            <div class="card__count">
                                                                <span>{{ $loveone->lockbox->data->documents }}</span> Files in your lockbox <br />
                                                                <i class="gray">Last updated {{ $loveone->lockbox->data->l_document }}</i>
                                                            </div>
                                                            {{-- @if ($loveone->careteam->role == 'admin') --}}
                                                                <a class="btn btn-primary btn-sm mt-2" href="{{ route('lockbox.index', [$loveone->slug] )}}">View Documents</a>
                                                            {{-- @endif --}}
                                                        </div>
                                                    </a>
                                                    
                                                </div>
                                            </div>

                                            {{-- Careteam --}}
                                            <div class="card widget team shadow-sm mb-3 mr-lg-3">
                                                <div class="card-body">
                                                    
                                                    <a href="{{ route('careteam', [$loveone->slug] )}}" class="">

                                                        <span class="float-right " tabindex="0" data-toggle="tooltip" title="Invite family, friends and professionals to join your loved one’s CareTeam. ">
                                                            <i class="fas fa-info-circle text-black-50"></i>
                                                        </span>
                                                        <h5 class="card-title"><i class="fas fa-users fa-2x"></i> CareTeam</h5>
                                                    </a>
                                                    <div class="card-text">
                                                        <div class="card__member-count"><span>{{$loveone->members->count()}}</span> Member(s)</div>
                                            
                                                        <div class="pl-3 avatar-imgs">
                                                            @foreach ($loveone->members as $member)
                                                                
                                                                <img src="{{asset($member->photo)}}" class="member-img" title="{{$member->name . ' ' . $member->lastname}}" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                                            @endforeach
                                                        </div>
                                                        
                                                        {{-- @if ($loveone->careteam->role == 'admin') --}}
                                                            <a href="{{ route('careteam', [$loveone->slug] )}}" class="btn btn-primary btn-sm">View Members</a>
                                                        {{-- @endif --}}
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Medlist --}}
                                            {{-- <div class="card widget medlist shadow-sm mb-3">
                                                <div class="card-body">
                                            
                                                    <a href="{{ route('medlist', [$loveone->slug] )}}" class="medlist">

                                                        <span class="float-right " tabindex="0" data-toggle="tooltip" title="Track prescription information and manage medication schedules.">
                                                            <i class="fas fa-info-circle text-black-50"></i>
                                                        </span>
                                                        <h5 class="card-title"><i class="fas fa-prescription-bottle-alt fa-2x"></i> Medlist</h5>
                                                        <p class="card-text medlist-today ">
                                                            <div class="card__count">
                                                                <b>{{$loveone->medlist->data->count_medications}}</b> Medications for today <br>
                                                                @if ( $loveone->medlist->data->next_dosage != '')
                                                                    <i class="gray">{{$loveone->medlist->data->next_dosage}}</i>
                                                                @else
                                                                    <i class="gray">No dosage</i>
                                                                @endif
                                                            </div>
                                                        </p>
                                                        {{-- @if ($loveone->careteam->role == 'admin') -}}
                                                            <a href="{{ route('medlist', [$loveone->slug] )}}" class="btn btn-primary btn-sm text-white">View Medications</a>
                                                        {{-- @endif -}}
                                                    </a>
                                                </div>
                                            </div> --}}



                                            {{-- Discussions --}}
                                            {{-- <div class="card widget message shadow-sm mb-3 mr-lg-3">
                                                <div class="card-body">
                                            
                                                    <a href="{{ route('discussions', [$loveone->slug] )}}" class="hub">

                                                        <span class="float-right " tabindex="0" data-toggle="tooltip" title="Document specific conversations regarding the care of your loved one.  ">
                                                            <i class="fas fa-info-circle text-black-50"></i>
                                                        </span>
                                                        <h5 class="card-title"><i class="fas fa-comments fa-2x"></i> Discussions</h5>
                                                        <div class="card-text">
                                                            <div class="card__count">
                                                                <span >{{ $loveone->messages->data->num_message }}</span> Unread Message(s) <br>
                                                                <i class="gray" >Last message from {{ $loveone->messages->data->last_message }}</i>
                                                            </div>

                                                            {{-- @if ($loveone->careteam->role == 'admin') -}}
                                                                <a class="btn btn-primary btn-sm" href="{{ route('discussions', [$loveone->slug] )}}">View Messages</a>
                                                            {{-- @endif -}}
                                                        </div>
                                                    </a>
                                                </div>
                                            </div> --}}

                                            {{-- resources --}}
                                            {{-- <div class="card widget resources shadow-sm mb-3  mr-lg-3">
                                                <div class="card-body">
                                            
                                                    <a href="{{ route('resources', [$loveone->slug] )}}" class="hub">
                                                        <span class="float-right " tabindex="0" data-toggle="tooltip" title="Access current information based on your loved one's medical profile. Receive expert guidance on caregiver well-being.">
                                                            <i class="fas fa-info-circle text-black-50"></i>
                                                        </span>
                                                        <h5 class="card-title"><i class="fas fa-globe fa-2x"></i> Resources</h5>
                                                        <div class="card-text">
                                                            <div class="card__count">
                                                                @if (count($loveone->resources['articles']) > 0)
                                                                    
                                                                    <span>{{count($loveone->resources['articles'])}}</span> new articles and topics <br>   
                                                                    <i class="gray">Last article published {{date('M, d Y')}}</i>
                                                                @else
                                                                    
                                                                    <i class="gray">No articles of your interest have been published</i>
                                                                @endif
                                                            </div>
                                                            
                                                            {{-- @if ($loveone->careteam->role == 'admin') -}}
                                                                <a class="btn btn-primary btn-sm" href="{{ route('resources', [$loveone->slug] )}}">View Resources</a>
                                                            {{-- @endif -}}
                                                        </div>
                                                    </a>
                                                </div>
                                            </div> --}}

                                            {{-- vitals --}}
                                            {{-- <div class="card widget vitals shadow-sm mb-3 mr-lg-3">
                                                <div class="card-body">
                                            
                                                    <a href="#" class="hub">
                                                        <h5 class="card-title text-black-50"><i class="fas fa-heartbeat fa-2x"></i> Vital stats</h5>
                                                        <div class="card-text">
                                                            ...
                                                            
                                                            {{-- @if ($loveone->careteam->role == 'admin') -}}
                                                                <a class="btn btn-primary btn-sm disabled" href="{{ route('resources', [$loveone->slug] )}}">View Vitals</a>
                                                            {{-- @endif -}}
                                                        </div>
                                                    </a>
                                                </div>
                                            </div> --}}

                                            {{-- blank --}}
                                            <div class="card widget resources shadow-sm mb-3">
                                                <div class="card-body">
                                            
                                                    
                                                </div>
                                            </div>
                                            





                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (sizeof($loveones) > 1)
                    
                        <a class="carousel-control-prev" href="#" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                
                    @endif
                
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

    .lovedone{
        display: none !important;
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
        
        // loveone = localStorage.getItem('loveone');
        // if(loveone != null){
        //     loveone = JSON.parse(loveone);
        //     l_id   = loveone.id;
        //     l_slug = loveone.slug;
        // } else {
        //     l_id   = '{{ $loveones[0]->id }}';
        //     l_slug = '{{ $loveones[0]->slug }}';
        // }
        l_id   = '{{ $loveones[0]->id }}';
        l_slug = '{{ $loveones[0]->slug }}';
        this.refreshWidgets(l_id, l_slug);
        // $('#homeCarousel .carousel-item.loveone-' + l_id + ' .btn').attr('disabled', true).text('Selected').addClass('disabled').removeClass('btn-primary').addClass('btn-secondary');
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
        },
        setLoveone: function(loveone_id) {

            localStorage.removeItem('loveone');
            current_loveone_ = $('.carousel-inner .loveone-' + loveone_id).data('info');
            // current_loveone_.color = $('.carousel-item.loveone-' + current_loveone_.id).attr('data-color');
            localStorage.setItem('loveone', JSON.stringify(current_loveone_));
            
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

    // Enable tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

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
    });

    $('.carousel-control-next').click(function(){
        $('.carousel').carousel('next');
    });

    $('.carousel').on('slide.bs.carousel', function (e) {
        $active_id = $('.carousel-item.slide-' + e.to).data('id');
        $active_slug = $('.carousel-item.slide-' + e.to).data('slug');
        home.refreshWidgets($active_id, $active_slug);
    })

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
