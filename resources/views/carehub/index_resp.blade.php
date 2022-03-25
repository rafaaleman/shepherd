@extends('layouts.app')

@section('content')
<div class="container" id="carehub">
    <div class="row mb-3 align-items-center justify-content-center">
        
        <div class="col-12 row">
            <div class="col-2 col-md-1 px-2"><a href="#/" v-on:click="calendarType(1)" data-tpe="1" class="btn-event btn-lg btn-block rounded-pill btn-outline-pink rounded-top btn-outline-pink-active menuDate menuDateCarepoints" id="Today">Today</a></div>
            <div class="col-2 col-md-1 px-2"><a href="#/" v-on:click="calendarType(2)" data-tpe="2" class="btn-event btn-lg btn-block rounded-pill btn-outline-pink rounded-top menuDate menuDateCarepoints" id="Week">Week</a></div>
            <div class="col-2 col-md-1 px-2"><a href="#/" v-on:click="calendarType(3)" data-tpe="3" class="btn-event btn-lg btn-block rounded-pill btn-outline-pink rounded-top menuDate menuDateCarepoints" id="Month">Month</a></div>
            <div class="col-6 col-md-9 px-2">
                <a href="{{route('carehub.event.form.create',[$loveone->slug])}}" class="float-right btn btn-primary btn-lg rounded-pill text-white mr-2 btn-carepoints">
                    Add New Task
                </a>
                <!-- <input  id="carehub_datepicker" type="text" class="form-control no-border mt-3 mt-md-1" name="date" required autocomplete="off"  placeholder="Select Date" > -->
                <!-- <div class="input-group mb-3 mt-3 mt-md-1">
                    <div class="input-group-prepend" onClick="$('#carehub_datepicker').datepicker('show');">
                        <span class="input-group-text fa fa-calendar" id="basic-addon1"></span>
                    </div>
                    <input  id="carehub_datepicker" data-date-end-date="0d" type="text" class="form-control no-border " name="date" required autocomplete="off"  placeholder="Select Date" >
                </div> -->
            </div>
        </div>
    </div>


    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="col-12 ">
                <div class="row mb-3">

                    <div class="col-2 col-md-2 px-0">
                        <select class="custom-select mr-sm-2 sel-no-bor" v-model="mm" id="inlineFormCustomSelect" v-on:change="searchCalendar()" style="background: #fff url({{asset('img/icons/Angle-down.png')}}) right 0.75rem center/15px 16px no-repeat;">
                           
                            @foreach($months as $cve => $month)
                                <option value="{{$cve}}">{{$month}}</option>

                            @endforeach
                            
                        </select>
                    </div>
                    <div class="col-2 col-md-2 px-2">
                        <select class="custom-select mr-sm-2 sel-no-bor" v-model="yyyy" id="inlineFormCustomSelect" v-on:change="searchCalendar()" style="background: #fff url({{asset('img/icons/Angle-down.png')}}) right 0.75rem center/15px 16px no-repeat;">
                            @for($i = 1997; $i <= date('Y'); $i++)
                                <option value="{{$i}}" >{{$i}}</option>
                            @endfor
                            
                        </select>
                    </div>
                    <div class="col-3 col-md-8 px-2">
                        <button type="button" class="float-right btn btn-outline-light-button btn-sm btn-icon right" id="right" v-on:click="dateNext()"> <i class="fas fa-angle-right"></i> </button>&nbsp;&nbsp;
                        <button type="button" class="float-right btn btn-outline-light-button btn-sm btn-icon mr-3 left" id="left" v-on:click="datePrev()"> <i class="fas fa-angle-left"></i> </button>
                    </div>
                </div>

            </div>


            <div class="col-md-12" id="month_div">
                <div class="row Rectangle-289">
                    
                        <div class="col text-center px-0 mon" >Sun</div>
                        <div class="col text-center px-0 mon" >Mon</div>
                        <div class="col text-center px-0 mon" >Tue</div>
                        <div class="col text-center px-0 mon" >Wed</div>
                        <div class="col text-center px-0 mon" >Thu</div>
                        <div class="col text-center px-0 mon" >Fri</div>
                        <div class="col text-center px-0 mon" >Sat</div>
                </div>
                <template v-for="week in calendar_month">
                    <div class="row row-cols-7 border-bottom-1">
                        
                        <template v-for="day in week.datos">
                            <div class="col text-center col-day px-0" >
                                <div v-if="month_name == day.mes" class="day_month">
                                    <div v-if="now == day.fecha" :class="day.fecha" class="number_day box-now">
                                        @{{ day.dia  }} 
                                    </div>
                                    <div v-else-if="date_events == day.fecha" class="number_day box-now" :class="day.fecha">
                                        @{{ day.dia  }} 
                                    </div>
                                    <div v-else class="number_day" :class="day.fecha" data-events="0">
                                        @{{ day.dia  }} 
                                        <div class="event_calendar pt-lg-1" :class="'event-calendar-'+day.fecha" >
                                            <span class="d-block d-sm-block d-md-none event-point" :class="'event-calendar-'+day.fecha+'-point'"></span>
                                            <span class="event-name-calendar d-none d-sm-none d-md-inline-block eventname" :class="'event-calendar-'+day.fecha+'-0'" ></span>
                                            <span class="event-name-calendar d-none d-sm-none d-md-inline-block eventname" :class="'event-calendar-'+day.fecha+'-1'" ></span>
                                        </div>
                                        <!-- <div class="row w-100" >
                                            <div class="col-12 text-truncate">
                                                Este texto es bastante largo y se truncará una vez que se muestre.
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="number_day number_day_ligth" :class="day.fecha">
                                        @{{ day.dia  }} 
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>



<!-- 
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
    </div> -->




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

    
</div>




@endsection
@push('styles')
<link href="{{asset('css/iconos_datepicker.css')}}" rel="stylesheet">
<link href="{{asset('css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
<link href="{{asset('css/bootstrap-datepicker.standalone.min.css')}}" rel="stylesheet">
<link href="{{asset('css/bootstrap-datepicker3.min.css')}}" rel="stylesheet">
<link href="{{asset('css/bootstrap-datepicker3.standalone.min.css')}}" rel="stylesheet">
<style>
   


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
            this.valButtons();
        },
        data: {

            dd: '',
            mm: '{{date("m")}}',
            yyyy:'{{date("Y")}}',
            date_events_month:'',
            limit_buttons_ini: moment().subtract('years', 25),
            limit_buttons: moment(),
            type: 3,
            date: '',
            date_events:'',
            events: '',
            loveone_id: '',
            current_slug: '',
            current_members: '',
            careteam_url: '',
            date_title: '',
            calendar: '',
            calendar_month:'',
            week_div: '',
            day_div: '',
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
                this.calendar = '';
                this.calendar_month = '';
                this.week_div = '';
                this.day_div = '';
                this.count_discussion = 0;
                this.count_event = 0;
                this.getEvents();
                this.getCalendar();
                this.eventInCalendar();
            },
            calendarType: function(type) {
                //alert(type);
                this.type = type;
                if (type == 1) {
                    // $("#day_div").removeClass("d-none");
                    // $("#week_div, #month_div").addClass("d-none");
                    $("#Today").addClass("btn-outline-pink-active").removeClass("disabled");
                    $("#Week, #Month").removeClass("btn-outline-pink-active");
                    this.getEvents();
                } else if (type == 2) {
                    // $("#week_div").removeClass("d-none");
                    // $("#day_div, #month_div").addClass("d-none");
                    $("#Week").addClass("btn-outline-pink-active").removeClass("disabled");
                    $("#Today, #Month").removeClass("btn-outline-pink-active");
                    this.getEvents();

                } else if (type == 3) {
                    // $("#month_div").removeClass("d-none");
                    // $("#day_div, #week_div").addClass("d-none");
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
            datePrev:function(){
                //console.log(this.mm);
                if(this.mm == '01'){
                    this.yyyy = parseInt(this.yyyy) - 1;
                    this.mm = '12';
                }else{
                    var m = parseInt(this.mm) - 1;
                    if(m < 10){
                        this.mm = '0' + m;
                    }else{
                        this.mm = m;
                    }
                }
                this.valButtons();
                this.getEvents();
                this.getCalendar();
            },
            dateNext:function(){
                if(this.mm == '12'){
                    this.mm = '01';
                    this.yyyy = parseInt(this.yyyy) + 1;
                }else{
                    var m = parseInt(this.mm) + 1;
                    if(m < 10){
                        this.mm = '0' + m;
                    }else{
                        this.mm = m;
                    }
                }
                this.valButtons();
                this.getEvents();
                this.getCalendar();
            },
            valButtons:function(){
                var month = this.mm;
                var year = this.yyyy;
                var month = parseInt(month);
                if(month == 12){
                    month = '01';
                    year = parseInt(year) + 1;
                }else{
                    month = month + 1;
                    if(month < 10){
                        month = '0' + month;
                    }else{
                        month = month;
                    }
                }

                var date = moment(year + '-' + month + '-01');
               // console.log(date);
                if(date > this.limit_buttons){
                    $("#right").attr('disabled', true);
                }else{
                    if($("#right").is(':disabled')){
                        $("#right").attr('disabled', false);
                    }
                }




                var month2 = this.mm;
                var year2 = this.yyyy;
                var month2 = parseInt(month2);
                if(month2 == 1){
                    month2 = '12';
                    year2 = parseInt(year2) - 1;
                }else{
                    month2 = month2 - 1;
                    if(month2 < 10){
                        month2 = '0' + month2;
                    }else{
                        month2 = month2;
                    }
                }

                var date2 = moment(year2 + '-' + month2 + '-01');
                
                if(date2 < this.limit_buttons_ini){
                    $("#left").attr('disabled', true);
                }else{
                    if($("#left").is(':disabled')){
                        $("#left").attr('disabled', false);
                    }
                }






                //console.log(date);
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
                    this.eventInCalendar();
                }).catch(error => {

                    msg = 'There was an error getting calendar. Please reload the page';
                    swal('Error', msg, 'error');
                });

            },
            searchCalendar: function() {
                $('#calendar').hide();
                $('#calendar_div .loading').show();

                this.date_events_month = this.yyyy + "-" + this.mm + "-01";

                var url = '{{ route("carehub.getCalendar", ["*DATE*"]) }}';
                url = url.replace('*DATE*', this.date_events_month);
                axios.get(url).then(response => {
                    this.calendar_month = response.data.calendar;
                    this.month_name = response.data.month;
                   // this.week_div = response.data.week;
                   // this.day_div = response.data.day;
                    $('#calendar_div .loading').hide();
                    $('#calendar').show();
                    this.valButtons();
                    this.eventInCalendar();
                }).catch(error => {

                    msg = 'There was an error getting calendar. Please reload the page';
                    swal('Error', msg, 'error');
                });

            },
            searchEvents: function() {

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
                        //this.eventInCalendar();
                    } else {

                    }

                    $('.loading-events').hide();
                    $('.events .card-events-date').show();
                    this.eventInCalendar();
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

            eventInCalendar: function(){
                console.log(this.events);
                $(".eventname").html('');
                $(".eventpoint").html('');
                $.each(this.events,function(index, day){
                    console.log(index);
                    console.log(day);
                   // console.log(".event-calendar-"+day.date + "  " + day.name + "           " + num);
                   // console.log(day.data[0]);
                   // console.log(day.data[1]);
                   console.log(day.date);
                    $(".event-calendar-"+day.date+"-0").html("• " + day.data[0]['name'] );
                    $(".event-calendar-"+day.date+"-point").html("•");
                    if(day.data[1] != undefined){
                        $(".event-calendar-"+day.date+"-1").html("• " + day.data[1]['name'] );
                    }
                    //addClass("box-event rounded-circle").removeClass("week-box");
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