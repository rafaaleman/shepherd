@extends('layouts.app')

@section('content')
<div class="container" id="carehub">
    <div class="row mb-3 align-items-center justify-content-center">
        
        <div class="col-12 d-none d-sm-none d-lg-block mb-3">
            
            <a href="{{route('carehub.discussion.form.create',[$loveone->slug])}}" class="float-right btn  btn-primary btn-lg  rounded-pill text-white mr-2">
                Create Discussion
            </a>
            <a href="{{route('carehub.event.form.create',[$loveone->slug])}}" class="float-right btn  btn-primary btn-lg  rounded-pill text-white mr-2">
                Assign A Task
            </a>
            
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12 row">
            <div class="col-4 col-md-3 px-2"><button type="button" v-on:click="calendarType(1)" data-tpe="1" class="btn-event btn btn-lg btn-block rounded-pill btn-outline-pink rounded-top btn-outline-pink-active menuDate" id="Today">Today</button></div>
            <div class="col-4 col-md-3 px-2"><button type="button" v-on:click="calendarType(2)" data-tpe="2" class="btn-event btn btn-lg btn-block rounded-pill btn-outline-pink rounded-top menuDate" id="Week">Week</button></div>
            <div class="col-4 col-md-3 px-2"><button type="button" v-on:click="calendarType(3)" data-tpe="3" class="btn-event btn btn-lg btn-block rounded-pill btn-outline-pink rounded-top menuDate" id="Month">Month</button></div>
            <div class="col-12 col-md-3 px-2" id="month-date">
                <!-- <input  id="carehub_datepicker" type="text" class="form-control no-border mt-3 mt-md-1" name="date" required autocomplete="off"  placeholder="Select Date" > -->
                <div class="input-group mb-3 mt-3 mt-md-1">
                    <div class="input-group-prepend" onClick="$('#carehub_datepicker').datepicker('show');">
                        <span class="input-group-text fa fa-calendar" id="basic-addon1"></span>
                    </div>
                    <input  id="carehub_datepicker" data-date-end-date="0d" type="text" class="form-control no-border " name="date" required autocomplete="off"  placeholder="Select Date" >
                </div>
            </div>
        </div>
    </div>

    <div class="row align-items-center justify-content-center px-3 py-3" id="calendar_div">
        <div class="loading text-center w-100">
            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading calendar...
        </div>
        <div class="col-md-12 row" id="day_div">
            <template v-for="day in day_div">

                <div class="col col-md-1 text-center col-day" :class="day.class">
                    <div class="pb-3">
                        <div v-if="now == day.fecha" class="rounded-circle align-self-center box-now" :class="day.fecha">
                            @{{ day.dia  }} <br>
                            <small>@{{ day.mes  }}</small>
                        </div>
                        <div v-else-if="date_events == day.fecha" class="rounded-circle align-self-center box-now box-search" :class="day.fecha">
                            @{{ day.dia  }} <br>
                            <small>@{{ day.mes  }}</small>
                        </div>
                        <div v-else class="box-day align-self-center" >
                            @{{ day.dia  }} <br>
                            <small>@{{ day.mes  }}</small>
                        </div>
                    </div>

                </div>


            </template>
        </div>
        <div class="col-md-12 row d-none mb-3" id="week_div">
            <template v-for="day in week_div">
                <template v-if="day.class == ''">
                    <div class="col col-md-1 text-center col-day bg-rose day_month" :class="day.class" >
                        <template>
                            <div class="align-self-center week-box" :class="day.fecha">
                                @{{ day.dia  }} <br>
                                <small>@{{ day.mes  }}</small>
                            </div>
                        </template>
                    </div>
                </template>
                <template v-else>
                    <div class="col col-md-1 text-center col-day day_month_web" :class="day.class" >
                        <template>
                            <div class="box-day" :class="day.fecha">
                                @{{ day.dia  }} <br>
                                <small>@{{ day.mes  }}</small>
                            </div>
                        </template>
                    </div>
                </template>
            </template>
        </div>
        <div class="col-md-12 d-none" id="month_div">
            <template v-for="week in calendar_month">
                <div class="row border-bottom-1 mb-3">
                    <template v-for="day in week.datos">
                        <div class="col text-center col-day px-0" >
                            <div v-if="month_name == day.mes" class="pb-3">
                                <div v-if="now == day.fecha" :class="day.fecha" class="rounded-circle align-self-center box-now">
                                    @{{ day.dia  }} <br>
                                    <small>@{{ day.mes  }}</small>
                                </div>
                                <div v-else-if="date_events == day.fecha" class="rounded-circle align-self-center box-now box-search" :class="day.fecha">
                                    @{{ day.dia  }} <br>
                                    <small>@{{ day.mes  }}</small>
                                </div>
                                <div v-else class="box-day" :class="day.fecha">
                                    @{{ day.dia  }} <br>
                                    <small>@{{ day.mes  }}</small>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>


    <div class="loading-discussions w-100 text-center">
        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading discussions...
    </div>

    
    <div v-if="count_discussion > 0" >
   
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 discussions">
                        <div class="pl-3 avatar-imgs">
                            <h6 class="card-title font-weight-bold mb-5">
                                Discussion(s) 
                                
                            </h6>
                            <template v-for="(discussion,index) in discussions" >
                                <div class="row border-bottom-1 mb-3" v-if="discussion.status">
                                    
                                    <div class="col-12 row eventinf">

                                        <div class="col-12 col-lg-6">
                                            <h5 class="font-weight-bold">@{{discussion.name}}</h5>
                                            <p class="text-muted">@{{discussion.notes}}</p>
                                        </div>
                                        <div class="widget team col-12 col-lg-6 p-0">
                                            <div class="d-flex">
                                                <div class="pl-0 pl-md-3 pl-lg-3 avatar-imgs ml-0 ml-lg-0 ml-lg-auto">
                                                    <p>
                                                        <span class="btn btn-link" v-on:click="discussionDetails(discussion.id)" style="text-decoration: none;">
                                                            <template v-for="member in discussion.members">
                                                                <img :src="member.user.photo" class="member-img" :title="member.user.name + ' ' + member.user.lastname" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                                            </template>
                                                            @{{discussion.messages.length}} <i class="fa fa-comments" style="font-size:15px;"></i>
                                                        </span>
                                                        <a href="" class="text-danger " v-on:click.prevent="deleteDiscussion(discussion)">
                                                            <i class="fa fa-archive"></i>
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <template v-if="discussions.length != (index + 1) && discussion.status == 1"  >
                                    <hr>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    <div v-else class=" w-100 text-center">No discussions found...</div>



    <div class="loading-events w-100 text-center">
        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading events...
    </div>
    <div v-if="count_event > 0">
    <template v-for="event in events" >
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 events">
                        <div class="pl-3 avatar-imgs">
                            <h6 class="card-title font-weight-bold mb-5">
                                @{{event.title}} 
                                
                            </h6>
                            <template v-for="(day,index) in event.data" >
                                <div class="row border-bottom-1 mb-3" v-if="day.status">
                                    <div class="col-4 col-sm-3 col-lg-2">
                                        <div class="bottom-50 end-50">
                                            <h3 class="text-danger font-weight-bold text-uppercase time">
                                                @{{day.time_cad_gi}} <br /><small style="font-size:.6em">@{{day.time_cad_a}}</small>
                                               
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-8 col-sm-9 col-lg-10 row eventinf">

                                        <div class="col-12 col-lg-6">
                                            <h5 class="font-weight-bold">@{{day.name}}</h5>
                                            <p class="text-muted">@{{day.location}}</p>
                                        </div>
                                        <div class="widget team col-12 col-lg-6 p-0">
                                            <div class="d-flex">
                                                <div class="pl-0 pl-md-3 pl-lg-3 avatar-imgs ml-0 ml-lg-0 ml-lg-auto">
                                                    <p>
                                                        <span class="btn btn-link" v-on:click="eventDetails(day.id)" style="text-decoration: none;">
                                                            <template v-for="member in day.members">
                                                                <img :src="member.user.photo" class="member-img" :title="member.user.name + ' ' + member.user.lastname" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                                            </template>
                                                            @{{day.count_messages}} <i class="fa fa-comments" style="font-size:15px;"></i>
                                                        </span>
                                                        <a href="" class="text-danger " v-on:click.prevent="deleteEvent(day)">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <template v-if="event.data.length != (index + 1)">
                                    <hr>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    </div>
    <div v-else class=" w-100 text-center">No tasks found...</div>

    <center>
        <div class=" d-block d-sm-block d-lg-none mb-3">
            
            <a href="{{route('carehub.event.form.create',[$loveone->slug])}}" class="btn btn-primary btn-lg  rounded-pill text-white mt-4">
                Assign A Task
            </a>
            <a href="{{route('carehub.discussion.form.create',[$loveone->slug])}}" class="btn btn-primary btn-lg  rounded-pill text-white mt-4">
                Create Discussion
            </a>
        </div>
    </center>


    <form action="{{route('carehub.getEvent')}}" method="post" id="formDetail">
        @csrf
        <input type="hidden" name="id" id="id" :value="event_url.id">
        <input type="hidden" name="slug" :value="event_url.slug">
    </form>

    <form action="{{route('carehub.getDiscussion')}}" method="post" id="formDetailDiscussion">
        @csrf
        <input type="hidden" name="id" id="id" :value="discussion_url.id">
        <input type="hidden" name="slug" :value="discussion_url.slug">
    </form>
</div>




@endsection
@push('styles')
<link href="{{asset('css/iconos_datepicker.css')}}" rel="stylesheet">
<link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
<link href="{{asset('css/bootstrap-datepicker.standalone.min.css')}}" rel="stylesheet">
<link href="{{asset('css/bootstrap-datepicker3.min.css')}}" rel="stylesheet">
<link href="{{asset('css/bootstrap-datepicker3.standalone.min.css')}}" rel="stylesheet">
<style>
    .member-img {
        background-color: #fff;
        margin-left: -10px;
        width: 25px;
        border-radius: 50%;
    }

    .col-day {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .box-day {
        color: #cdcdd8;
        line-height: 1;
        font-size: 18px;
        line-height: 1.3;
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
    }

    .box-now {
        width: 50px;
        height: 50px;
        box-shadow: 0 5px 15px 0 rgba(94, 102, 137, 0.2);
        background-color: #d36582;
        color: #fff;
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
        align-items: center;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: 1;
        letter-spacing: normal;
    }

    .box-search {
        
        background-color: #D46A54 !important;
        
    }

    .box-event {
        width: 50px;
        height: 50px;
        box-shadow: 0 5px 15px 0 rgba(94, 102, 137, 0.2);
        background-color: #369bb6;
        color: #fff;
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
        align-items: center;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: 1;
        letter-spacing: normal;
    }

    

    .box-day small,
    .box-now small,
    .week-box small {
        font-size: 8px;
        text-transform: uppercase;
        font-weight: 400;

    }

    .day_month,
    .day_month_web {
        padding: 10px 0;
    }

    .day_month .week-box {
        background-color: #d36582;
        color: #fff;
        font-weight: 600;
        font-size: 18px;
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
        align-items: center;
        font-stretch: normal;
        font-style: normal;
        line-height: 1;
        letter-spacing: normal;
    }

    .bg-rose {
        background: #d36582;
    }

    .day_month:nth-child(4) {
        border-radius: 50px 0px 0px 50px !important;
    }

    .day_month:nth-child(10) {
        border-radius: 0px 50px 50px 0px !important;
    }

    @media only screen and (max-width: 400px) {
        .menuDate {
            font-size: .8rem;
            padding:7px 10px;
        }
        #calendar_div .col-day{
            padding-left:0px !important;
            padding-right:0px !important;
        }
        .box-event, .box-now{
            width:40px;
            height:40px;
        }
        .time{
            font-size:1.2rem;
        }
        .eventinf, .events{
            padding-left:5px !important;
            padding-right:5px !important;

        }

    }


</style>

@endpush
@push('scripts')
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>

<script>
    $("main").removeClass("py-4").addClass("py-0");
    $(function () {
        input_date();
    });
    const carehub = new Vue({
        el: '#carehub',
        created: function() {
            this.refreshWidgets({{$loveone->id}}, '{{$loveone->slug}}', '{{$to_day->format("Y-m-d")}}', '{{$to_day->format("Y-m")}}', '{{$to_day->format("Y-m-d")}}');
        },
        data: {
            type: 1,
            date: '',
            date_events:'',
            date_events_month:'',
            events: '',
            discussions: '',
            loveone_id: '',
            current_slug: '',
            current_members: '',
            careteam_url: '',
            date_title: '',
            calendar: '',
            calendar_month:'',
            week_div: '',
            day_div: '',
            month: '',
            month_name: '',
            now: '',
            event_url: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: "",
                loveone_id: "{{ $loveone->id ?? 0 }}",
                slug: "{{$loveone->slug}}"
            },
            discussion_url: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: "",
                loveone_id: "{{ $loveone->id ?? 0 }}",
                slug: "{{$loveone->slug}}"
            },
            count_discussion:'',
            count_event:'',
        },
        filters: {},
        computed: {},
        methods: {
            refreshWidgets: function(loveone_id, current_slug, date, month, now) {
                this.loveone_id = loveone_id;
                this.current_slug = current_slug;
                this.date_events = date;
                this.month = month;
                this.now = now;
                this.events = '';
                this.discussions = '';
                this.calendar = '';
                this.calendar_month = '';
                this.week_div = '';
                this.day_div = '';
                this.count_discussion = 0;
                this.count_event = 0;
                this.getEvents();
                this.getCalendar();
                this.getDiscussions();
            },
            calendarType: function(type) {
                //alert(type);
                this.type = type;
                if (type == 1) {
                    $("#day_div").removeClass("d-none");
                    $("#week_div, #month_div").addClass("d-none");
                    $("#Today").addClass("btn-outline-pink-active").removeClass("disabled");
                    $("#Week, #Month").removeClass("btn-outline-pink-active");
                    this.getEvents();
                } else if (type == 2) {
                    $("#week_div").removeClass("d-none");
                    $("#day_div, #month_div").addClass("d-none");
                    $("#Week").addClass("btn-outline-pink-active").removeClass("disabled");
                    $("#Today, #Month").removeClass("btn-outline-pink-active");
                    this.getEvents();
                } else if (type == 3) {
                    $("#month_div").removeClass("d-none");
                    $("#day_div, #week_div").addClass("d-none");
                    $("#Month").addClass("btn-outline-pink-active").removeClass("disabled");
                    $("#Today, #Week").removeClass("btn-outline-pink-active");
                    if(this.date_events_month == ''){
                        this.getEvents();
                    }else{
                        this.searchEvents();
                    }
                } else {
                    alert();
                }
                
                
            },
            getEvents: function() {

                //console.log("current_slug " + this.current_slug  +  ", loveone_id" + this.loveone_id +  ", date" + this.date_events, ", events" + this.events);

                $('.events .card-events-date').hide();
                $('.loading-events').show();
                var url = '{{ route("carehub.getEvents", ["*SLUG*","*DATE*","*TYPE*"]) }}';
                url = url.replace('*SLUG*', this.current_slug);
                url = url.replace('*DATE*', this.date_events);
                url = url.replace('*TYPE*', this.type);
                axios.get(url).then(response => {

                    if (response.data.success) {
                        this.events = response.data.data.events;
                        this.date_title = response.data.data.date_title;
                        this.count_event = this.events.length;
                        this.eventInCalendar();
                    } else {

                    }

                    $('.loading-events').hide();
                    $('.events .card-events-date').show();

                }).catch(error => {

                    msg = 'There was an error getting events. Please reload the page';
                    swal('Error', msg, 'error');
                });

            },
            getDiscussions: function() {

                //console.log("current_slug " + this.current_slug  +  ", loveone_id" + this.loveone_id +  ", date" + this.date_events, ", events" + this.events);

                $('.loading-discussions').show();

                var url = '{{ route("carehub.getDiscussions", ["*SLUG*"]) }}';
                url = url.replace('*SLUG*', this.current_slug);
               
                axios.get(url).then(response => {

                    if (response.data.success) {
                        this.discussions = response.data.data.discussions;
                        this.count_discussion = this.discussions.length;
                       // console.log(this.discussions);
                    } else {

                    }

                    $('.loading-discussions').hide();

                }).catch(error => {

                    msg = 'There was an error getting discussions. Please reload the page';
                    swal('Error', msg, 'error');
                });

            },
            getCalendar: function() {


                $('#calendar').hide();
                $('#calendar_div .loading').show();

                var url = '{{ route("carehub.getCalendar", ["*DATE*"]) }}';
                url = url.replace('*DATE*', this.date_events);
                axios.get(url).then(response => {
                    this.calendar = response.data.calendar;
                    this.calendar_month = response.data.calendar;
                    this.month_name = response.data.month;
                    this.week_div = response.data.week;
                    this.day_div = response.data.day;
                    $('#calendar_div .loading').hide();
                    $('#calendar').show();

                }).catch(error => {

                    msg = 'There was an error getting calendar. Please reload the page';
                    swal('Error', msg, 'error');
                });

            },
            searchCalendar: function() {
                $('#calendar').hide();
                $('#calendar_div .loading').show();

                var url = '{{ route("carehub.getCalendar", ["*DATE*"]) }}';
                url = url.replace('*DATE*', this.date_events_month);
                axios.get(url).then(response => {
                    this.calendar_month = response.data.calendar;
                    this.month_name = response.data.month;
                   // this.week_div = response.data.week;
                   // this.day_div = response.data.day;
                    $('#calendar_div .loading').hide();
                    $('#calendar').show();

                }).catch(error => {

                    msg = 'There was an error getting calendar. Please reload the page';
                    swal('Error', msg, 'error');
                });

            },
            searchEvents: function() {

                //console.log("current_slug " + this.current_slug  +  ", loveone_id" + this.loveone_id +  ", date" + this.date_events, ", events" + this.events);

                $('.events .card-events-date').hide();
                $('.loading-events').show();
                var url = '{{ route("carehub.getEvents", ["*SLUG*","*DATE*","*TYPE*"]) }}';
                url = url.replace('*SLUG*', this.current_slug);
                url = url.replace('*DATE*', this.date_events_month);
                url = url.replace('*TYPE*', this.type);
                axios.get(url).then(response => {

                    if (response.data.success) {
                        this.events = response.data.data.events;
                        this.date_title = response.data.data.date_title;
                        this.count_event = this.events.length;
                        this.eventInCalendar();
                    } else {

                    }

                    $('.loading-events').hide();
                    $('.events .card-events-date').show();

                }).catch(error => {

                    msg = 'There was an error getting events. Please reload the page';
                    swal('Error', msg, 'error');
                });

            },
            eventDetails: function(event) {
                $("#formDetail #id").val(event);
                //this.event_url.id = event;
                $("#formDetail").submit();
                return false;
            },
            discussionDetails: function(discussion) {
                $("#formDetailDiscussion #id").val(discussion);
                //this.event_url.id = event;
                $("#formDetailDiscussion").submit();
                return false;
            },
            eventInCalendar(){
                $.each(this.events,function(index, day){
                    //console.log(day.date);
                    $("."+day.date).addClass("box-event rounded-circle").removeClass("week-box");
                });
            },
            deleteEvent: function(event){
                //console.log(event);
                swal({
                    title: "Warning",
                    text: "Are you sure to delete the '"+event.name+"' event?",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        "Yes, I'm sure!"
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {

                    if(isConfirm){
                        var url = '{{ route("carehub.event.delete") }}';
                        data = {
                            id: event.id,
                        };

                        axios.post(url, data).then(response => {
                           // console.log(response.data);
                            
                            if( response.data.success == true ){
                                //joinTeam.getInvitations();
                                msg = 'The event was deleted';
                                icon = 'success';
                                carehub.count_event--;
                                event.status = 0;
                            } else {
                                msg = 'There was an error. Please try again';
                                icon = 'error';
                            }
                            
                            swal(msg, "", icon);
                        });
                    }
                });
            },
            deleteDiscussion: function(discussion){
                //console.log(discussion);
                swal({
                    title: "Warning",
                    text: "Are you sure to archive the '"+discussion.name+"' discussion?",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        "Yes, I'm sure!"
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {

                    if(isConfirm){
                        var url = '{{ route("carehub.discussion.delete") }}';
                        data = {
                            id: discussion.id,
                        };

                        axios.post(url, data).then(response => {
                            console.log(response.data);
                            
                            if( response.data.success == true ){
                                //joinTeam.getInvitations();
                                msg = 'The discussion was archived';
                                icon = 'success';
                                discussion.status = 0;
                                carehub.count_discussion--;
                            } else {
                                msg = 'There was an error. Please try again';
                                icon = 'error';
                            }
                            
                            swal(msg, "", icon);
                        });
                    }
                });
            }

        },
    });

    function input_date(){
        $('#carehub_datepicker').datepicker({
                format: 'mm/yyyy',
                minViewMode: 'months',
                orientation: 'bottom'
        }).on('changeDate', function(e) {
        // `e` here contains the extra attributes
       // console.log(e.format('yyyy-mm-dd'));
        carehub.date_events_month = e.format('yyyy-mm-dd');
        carehub.searchCalendar();
        carehub.searchEvents();
    });

        // var picker = new Pikaday({
        //     field: document.getElementById('carehub_datepicker'),
        //     showWeekNumber: true,
        //     format: 'Y-MM-DD',
        //     maxDate: moment().toDate(),
        //     onSelect: function() {
        //         carehub.date_events = this.getMoment().format('YYYY-MM-DD');
        //         carehub.getCalendar();
        //         carehub.getEvents();
        //     }
        // });
    }
</script>
@endpush