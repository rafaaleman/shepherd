@extends('layouts.app')

@section('content')
<div class="container"  id="messages">
  
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 events">
                            <h3 class="card-title font-weight-bold mb-3">{{$event->name}}</h3>
                            <p class="mb-1" style="color:#cdcdd8" >{{$event->location}}</p>
                            <div style="padding-left:10px;">
                                @foreach($event->members as $assigned)
                                    <img src="{{ (!empty($assigned->user->photo) && $assigned->user->photo != null ) ? asset($assigned->user->photo) : asset('/img/no-avatar.png')}}" class="member-img" title="{{$assigned->user->photo}}" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                @endforeach
                            </div>
                            <hr class="mb-1">
                            <div class="w-100">
                                <div class="float-left font-weight-bold vertical-align-top">{{$event->date_title}}</div>
                                <div class="float-right text-danger font-weight-bold text-uppercase">{{$event->time_cad_gi}} {{$event->time_cad_a}}</div>
                            </div>

                            @if(!empty($event->notes))
                                <div class="bg w-100 mt-5 @if($event->creator_id == $id_careteam) creator @else member @endif">
                                    <img src="{{ (!empty($event->creator->photo) && $event->creator->photo != null ) ? asset($event->creator->photo) : asset('/img/no-avatar.png')}}" class="member-img" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                    {{$event->notes}}
                                </div>
                            @endif
                            
                    </div>
                </div>
            </div>
        </div>

        <div class="loading-events w-100 text-center d-none">
            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading comments...
        </div>

        <div id="messagess" class="w-100 row mx-0">
            @if(isset($event->messages) && $event->messages->count())
                
                @foreach($event->messages as $message)
                    @if($message->creator_id == $id_careteam)
                        <div class="row col-12 justify-content-center align-items-center m-2 creator" >
                            <div class="col-10 col-sm-11 alert alert-dark text-white text-left m-0 msg">
                                <img src="{{ (!empty($message->creator_img) && $message->creator_img != null ) ? asset($message->creator_img) : asset('/img/no-avatar.png')}}" class="member-img" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">
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
                                <img src="{{ (!empty($message->creator_img) && $message->creator_img != null ) ? asset($message->creator_img) : asset('/img/no-avatar.png')}}" class="member-img" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                {{$message->message}}
                            </div>
                            
                        </div>
                    @endif
                @endforeach
                @else
                <div class="loading-events w-100 text-center">
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
    .bg {
        padding: 16.5px 25px 13.1px 24px;
        background-color: #f7f7fa;
        left:15px;
        right:15px;
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
                event_id: "{{ $event->id ?? 0 }}",
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
                    var url = '{{ route("carehub.event.message.create") }}';
                    axios.post(url, this.message).then(response => {
                        console.log(response);

                        if (response.data.success) {
                            $("#messagess").append('<div class="row col-12 justify-content-center align-items-center m-2 creator" ><div class="col-10 col-sm-11 alert alert-dark text-white text-left m-0 msg"><img src="'+response.data.message.photo+'" class="member-img" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">'+ response.data.message.message +'</div><div class="col-2 col-sm-1 text-uppercase time justify-content-center align-items-center">'+ response.data.message.time_cad_gi + '<br/>'+ response.data.message.time_cad_a +'</div></div>');
                            this.message.message = '';
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