@extends('layouts.app')

@section('content')
<div class="container"  id="messages">
  
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 events">
                        <div class="w-100">
                            <div class="float-left font-weight-bold vertical-align-top">{{$discussion->date_title}}</div>
                            <div class="float-right text-danger font-weight-bold text-uppercase">{{$discussion->time_cad_gi}} {{$discussion->time_cad_a}}</div>
                        </div>
                            <h3 class="card-title font-weight-bold mb-3 mt-5">{{$discussion->name}}</h3>
                            <p class="mb-1" style="color:#cdcdd8" >{{$discussion->location}}</p>
                            <div style="padding-left:10px;" class="text-right">
                                <label for="name" class="col-form-label text-md-right" style="color: #78849e;font-size:12px;padding-right:10px">Assign to:  </label>
                                @foreach($discussion->members as $assigned)
                                    <img src="{{ (!empty($assigned->user->photo) && $assigned->user->photo != null ) ? $assigned->user->photo : '/img/no-avatar.png'}}" class="member-img" title="{{$assigned->user->photo}}" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                @endforeach
                            </div>
                            
                            @if(!empty($discussion->notes))
                                <div class="row">
                                    <div class="col-1 text-right">
                                        <img src="{{ (!empty($discussion->creator->photo) && $discussion->creator->photo != null ) ? $discussion->creator->photo : '/img/no-avatar.png'}}" class="member-img-cab" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                    </div>
                                    <div class="col-11">
                                        <span class="font-weight-bold d-block spn-name">
                                            {{$discussion->creator->name}} {{$discussion->creator->lastname}}
                                        </span>
                                        <span class="d-block spn-time">
                                        {{$discussion->date_title_msj}} at {{$discussion->time_cad_gi}} {{$discussion->time_cad_a}}
                                        </span>
                                    </div>
                                </div>
                                <div class="bg w-100 @if($discussion->creator_id == $id_careteam) creator @else member @endif">
                                    {{$discussion->notes}}
                                </div>
                            @endif
                            
                    </div>
                </div>
            </div>
        </div>


        
        <div class="loading-events w-100 text-center d-none">
            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading comments...
        </div>

        <div id="messagess" class="w-100 row mx-0 pt-3">
            @if(isset($discussion->messages) && $discussion->messages->count())
                
                @foreach($discussion->messages as $message)
                        <div class="col-6 pl-0 mb-2">
                            <img src="{{ (!empty($message->creator_img) && $message->creator_img != null ) ? $message->creator_img : '/img/no-avatar.png'}}" class="member-img-cab" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">
                            <span class="font-weight-bold spn-name">
                                {{$message->creator->user->name}} {{$message->creator->user->lastname}}
                            </span>
                        </div>
                        
                        <div class="col-6 text-right d-block spn-time align-bottom" style="bottom: -15px !important;">
                            
                                {{$message->date_title_msj}}
                        </div>
                    
                        <div class="col-12 alert alert-white text-left m-0 msg border mb-3" >
                            {{$message->message}}
                        </div>


                    {{--
                    @if($message->creator_id == $id_careteam)
                        <div class="row col-12 justify-content-center align-items-center m-2 creator" >
                            <div class="col-10 col-sm-11 alert alert-dark text-white text-left m-0 msg">
                                <img src="{{ (!empty($message->creator_img) && $message->creator_img != null ) ? $message->creator_img : '/img/no-avatar.png'}}" class="member-img" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                {{$message->message}}
                            </div>
                            <div class="col-2 col-sm-1 text-uppercase time justify-content-center align-items-center">
                                {{$message->time_cad_gi}}<br/>
                                {{$message->time_cad_a}}
                            </div>
                        </div>
                    @else
                        <div class="row col-12 justify-content-center align-items-center m-2 member" >
                            <div class="col-2 col-sm-1 text-uppercase time justify-content-center align-items-center text-right" style="left:-20px">
                                {{$message->time_cad_gi}} <br>
                                {{$message->time_cad_a}}
                            </div>
                            <div class="col-10 col-sm-11 alert alert-dark text-white text-right m-0 msg">
                                <img src="{{ (!empty($message->creator_img) && $message->creator_img != null ) ? $message->creator_img : '/img/no-avatar.png'}}" class="member-img" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                {{$message->message}}
                            </div>
                            
                        </div>
                    @endif
                    --}}
                @endforeach
                @else
                <div class="loading-events w-100 text-center no-messages">
                    <span class="spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> There are no messages...
                </div>
            @endif
        </div>

        <form method="POST" action="#" style="width: 100%;" class="mt-5" v-on:submit.prevent="createMessage()">
                @if($is_careteam)
                    <label for="name" class="col-form-label text-md-right" style="color: #78849e;font-size:10px">YOUR MESSAGE </label>
                    <textarea id="mesaje" rows="7" type="date" class="form-control" name="mesaje" required autocomplete="mesaje" v-model="message.message" maxlength="250"></textarea>
                    <div class=" w-100 justify-content-center my-4">
                        <center>
                            <button class="btn btn-primary btn-lg rounded-pill font-weight-bold text-white loadingBtn" data-loading-text="Saving..." id="saveBtn">
                                Reply
                            </button>
                        </center>
                    </div>
                @endif
            </div>
        </form>
</div>
@endsection
@push('styles')
<style>
    .member-img {
        background-color: #fff;
        margin-left: -10px !important;
        width: 25px;
        border-radius: 50%;
    }

    .member-img-cab {
        background-color: #fff;
        margin-top: 2px;
        margin-left: 0px !important;
        width: 25px;
        border-radius: 50%;
    }
    .spn-name{
        font-size: .8em;
    }

    .spn-time{
        font-size: .6em;
        color:#cdcdd8;
    }
    .bg {
        padding: 16.5px 0px 1.1px 4px;
        left: 15px;
        right: 15px;
        text-align: justify;
    }
    .creator div.msg{
        left:10px;
        background-color: #369bb6;
        border-radius:15px;

    }

    .member div.msg{
        right:10px;
        background-color: #78849e;
        border-radius:15px;
    }

    .creator div .member-img,.creator .member-img, .member div .member-img{
        margin-left: -35px !important;
        margin-top: -45px;
    }

    .member div .member-img, .member .member-img{
        margin-right: -35px !important;
        margin-top: -25px;
        float: right;
    }

    .time{
        color:#78849e;
        font-size:10px;
    }

    

</style>

@endpush
@push('scripts')
<script>
    $("main").removeClass("py-4").addClass("py-0");
    const message = new Vue ({
        el: '#messages',
        created: function() {
            console.log('messages');
        },
        data: {
            message: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                discussion_id: "{{ $discussion->id ?? 0 }}",
                message: "",
                creator_id: "{{ $id_careteam ?? 0 }}"
                
            }
            
        },
        filters: {
        },
        computed:{ 
        },
        methods: {
            createMessage: function() {
                //console.log(this.current_slug);
                $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                //this.event.phone = this.event.phone.replace(/\D/g,'');
                
               
                   // console.log(url_succes);
                    var url = '{{ route("carehub.discussion.message.create") }}';
                    axios.post(url, this.message).then(response => {
                        console.log(response);

                        if (response.data.success) {
                            $("#messagess").append('<div class="col-6 pl-0 mb-2"><img src="'+response.data.message.photo+'" class="member-img-cab" title="" data-bs-toggle="tooltip" data-bs-placement="bottom"><span class="font-weight-bold spn-name"> '+response.data.message.name+'</span></div><div class="col-6 text-right d-block spn-time align-bottom" style="bottom: -15px !important;">'+ response.data.message.time_cad_gi + ' '+ response.data.message.time_cad_a +'</div><div class="col-12 alert alert-white text-left m-0 msg border mb-3" >'+ response.data.message.message +'</div>');
                            
                            
                            this.message.message = '';
                            $(".no-messages").remove();
                        } else {
                            msg = 'There was an error. Please try again';
                            icon = 'error';
                        }

                        $('.loadingBtn').html('Save').attr('disabled', false);
                        swal(msg, "", icon);

                    }).catch(error => {

                        txt = "";
                        $('.loadingBtn').html('Save').attr('disabled', false);
                        $.each(error.response.data.errors, function(key, error) {
                            txt += error + '\n';
                        });

                        swal('There was an Error', txt, 'error');
                    });
                
            }
            
           
         
            
        },
    });

   
</script>
@endpush