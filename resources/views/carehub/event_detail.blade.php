@extends('layouts.app')

@section('content')
<div class="container" id="messages">

    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 events">
                    <h3 class="card-title font-weight-bold mb-3 ">{{$event->name}}</h3>
                    <p class="mb-1" style="color:#cdcdd8">{{$event->location}}</p>
                    <div style="padding-left:10px;" class="text-left">
                        <label for="name" class="col-form-label text-md-right" style="color: #78849e;font-size:12px;padding-right:10px">Assign to: </label>
                        @foreach($event->members as $assigned)
                        <img src="{{ (!empty($assigned->user->photo) && $assigned->user->photo != null ) ? $assigned->user->photo : '/img/no-avatar.png'}}" class="member-assing-img" title="{{$assigned->user->photo}}" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        @endforeach
                    </div>
                    <hr>
                    <div class="w-100 row">
                        <div class="col-6 text-left font-weight-bold vertical-align-top">{{$event->date_title}}</div>
                        <div class="col-6 text-right text-danger font-weight-bold text-uppercase">{{$event->time_cad_gi}} {{$event->time_cad_a}}</div>
                    </div>
                    
                    

                    @if(!empty($event->notes))
                        <div class="row pt-5">
                            <span class="task-date-title w-100">@{{event.created_at | formatDateCreated}}</span>
                            <div class="col-12 pl-4">
                                <img class="member-img-cab" src="{{ (!empty($event->creator->photo) && $event->creator->photo != null ) ? $event->creator->photo : '/img/no-avatar.png'}}" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <div class="message-details w-100 @if($event->creator_id == $id_careteam) creator @else member @endif">
                                    {{$event->notes}}
                                </div>
                            </div>
                            
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>



    <div class="loading-events w-100 text-center d-none">
        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading comments...
    </div>

                    



    <div id="messagess-task-detail" class="w-100 row pt-3" v-if="Object.keys(messages).length > 0">
        <template v-for="(day,index) in messages">
            <div class="w-100 badge-chat"><span class="badge badge-light-button ">@{{index | formatDate}}</span></div>
            <template v-for="msg in day">
                    
                    <div v-if="msg.creator_id == id_careteam" class="row col-12 justify-content-center align-items-center m-2 creator" >
                        <div class="col-10 col-sm-11 alert alert-dark text-white text-left m-0 msg">
                            <img v-if="msg.creator_img != null" :src="msg.creator_img" class="member-img" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">
                            <img v-else src="/img/no-avatar.png" alt="">
                            @{{msg.message}}
                        </div>
                        <div class="col-2 col-sm-1 text-uppercase time-msg-task justify-content-center align-items-center">
                            @{{msg.time_cad_gi}}<br />
                            @{{msg.time_cad_a}}
                        </div>
                    </div>
                    
                    <div v-else class="row col-12 justify-content-center align-items-center m-2 member">
                        <div class="col-2 col-sm-1 text-uppercase time-msg-task justify-content-center align-items-center text-right pr-0" style="left:-20px">
                            @{{msg.time_cad_gi}} <br>
                            @{{msg.time_cad_a}}
                        </div>
                        <div class="col-10 col-sm-11 alert alert-dark text-white text-right m-0 msg">
                            <img v-if="msg.creator_img != null" :src="msg.creator_img" class="member-img" title="" data-bs-toggle="tooltip" data-bs-placement="bottom">
                            <img v-else src="/img/no-avatar.png" alt="">
                            @{{msg.message}}
                        </div>

                    </div>
            </template>
        </template>
    </div>
    <div class="loading-events w-100 text-center no-messages" v-else>
        <span class="spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> There are no messages...
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




</style>

@endpush
@push('scripts')
<script>
    $("main").removeClass("py-4").addClass("py-0");
    const message = new Vue({
        el: '#messages',
        created: function() {
            
        },
        data: {
            message: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                event_id: "{{ $event->id ?? 0 }}",
                message: "",
                creator_id: "{{ $id_careteam ?? 0 }}"

            },
            event: @json($event),
            messages:@json($event->messages),
            msg_now: @json($msg),
            id_careteam: '{{$id_careteam}}',


        },
        filters: {
            formatDate: function(value) {
                return moment(String(value)).format('MMMM D, YYYY');
                
            },
            formatDateCreated: function(value) {
                return moment(String(value)).format('llll');
                
            },
        },
        computed: {},
        methods: {
            createMessage: function() {
                //console.log(this.current_slug);
                $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                //this.event.phone = this.event.phone.replace(/\D/g,'');


                // console.log(url_succes);
                var url = '{{ route("carehub.event.message.create") }}';
                axios.post(url, this.message).then(response => {
                    console.log(response.data);

                    if (response.data.success) {
                        if(message.messages[response.data.message.date] == undefined){
                            this.msg_now[response.data.message.date].push({
                            'creator_img': response.data.message.photo,
                            'time':  response.data.message.name,
                            'time_cad_gi':  response.data.message.time_cad_gi,
                            'time_cad_a':  response.data.message.time_cad_a,
                            'creator_id':  response.data.message.creator_id,
                            'message': response.data.message.message,
                            'id': response.data.message.id
                            });
                            message.messages = this.msg_now;
                        }else{
                            message.messages[response.data.message.date].push({
                                'creator_img': response.data.message.photo,
                                'time':  response.data.message.name,
                                'time_cad_gi':  response.data.message.time_cad_gi,
                                'time_cad_a':  response.data.message.time_cad_a,
                                'creator_id':  response.data.message.creator_id,
                                'message': response.data.message.message,
                                'id': response.data.message.id
                            });
                        }



                        this.message.message = '';
                    } else {
                        msg = 'There was an error. Please try again';
                        icon = 'error';
                    }

                    $('.loadingBtn').html('Save').attr('disabled', false);
                    //swal(msg, "", icon);

                }).catch(error => {

                    txt = "";
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    $.each(error, function(key, error) {
                        txt += error + '\n';
                    });

                    swal('There was an Error', txt, 'error');
                });

            }




        },
    });
</script>
@endpush