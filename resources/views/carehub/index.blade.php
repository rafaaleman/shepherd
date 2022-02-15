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

            </div>
        </div>
    </div>

    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="col-12 ">
                <div class="row mb-3">

                    <div class="col-4 col-md-2 px-0">
                        <select class="custom-select mr-sm-2 sel-no-bor" v-model="mm" id="monthsSelect" v-on:change="changeSelectCalendar()" style="background: #fff url({{asset('img/icons/Angle-down.png')}}) right 0.75rem center/15px 16px no-repeat;">

                            @foreach($months as $cve => $month)
                            <option value="{{$month['m']}}" class="op" id="{{$month['m']}}">{{$month['name']}}</option>

                            @endforeach

                        </select>
                    </div>
                    <div class="col-4 col-md-2 px-2">
                        <select class="custom-select mr-sm-2 sel-no-bor" v-model="yyyy" id="yearsSelect" v-on:change="changeSelectCalendarYear()" style="background: #fff url({{asset('img/icons/Angle-down.png')}}) right 0.75rem center/15px 16px no-repeat;">
                            @for($i = date('Y'); $i >= 1997; $i--) 
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor

                        </select>
                    </div>
                    <div class="col-4 col-md-8 px-2">
                        <button type="button" class="float-right btn btn-outline-light-button btn-sm btn-icon right" id="right" v-on:click="dateNext()"> <i class="fas fa-angle-right"></i> </button>&nbsp;&nbsp;
                        <button type="button" class="float-right btn btn-outline-light-button btn-sm btn-icon mr-3 left" id="left" v-on:click="datePrev()"> <i class="fas fa-angle-left"></i> </button>
                    </div>
                </div>

            </div>


            <div class="col-md-12" id="month_div">
                <div class="row Rectangle-289">

                    <div class="col text-center px-0 mon">Sun</div>
                    <div class="col text-center px-0 mon">Mon</div>
                    <div class="col text-center px-0 mon">Tue</div>
                    <div class="col text-center px-0 mon">Wed</div>
                    <div class="col text-center px-0 mon">Thu</div>
                    <div class="col text-center px-0 mon">Fri</div>
                    <div class="col text-center px-0 mon">Sat</div>
                </div>
                <template v-for="week in calendar_month">
                    <div class="row row-cols-7 border-bottom-1">

                        <template v-for="day in week.datos">
                            <div class="col text-center col-day px-0">
                                <div v-if="month_name == day.mes" class="day_month">
                                    <div v-if="now == day.fecha" :class="day.fecha" class="number_day box-now">
                                        @{{ day.dia  }}
                                    </div>
                                    <div v-else-if="date_events == day.fecha" class="number_day box-now" :class="day.fecha">
                                        @{{ day.dia  }}
                                    </div>
                                    <div v-else class="number_day" :class="day.fecha" data-events="0">
                                        @{{ day.dia  }}
                                        <div class="event_calendar pt-lg-1" :class="'event-calendar-'+day.fecha">
                                            
                                            <span class="d-block d-sm-block d-md-none event-point" :class="'event-calendar-'+day.fecha+'-point'" v-if="year_events[day.fecha]?.data[0]"><a :href="'#' + day.fecha" class="event-name-calendar">•</a></span>
                                            <span class="d-none d-sm-none d-md-inline-block eventname w-100 text-truncate" :class="'event-calendar-'+day.fecha+'-0'"><a :href="'#' + day.fecha" class="event-name-calendar">@{{year_events[day.fecha]?.data[0].name | txt_event}}</a></span>
                                            <span class="d-none d-sm-none d-md-inline-block eventname w-100 text-truncate" :class="'event-calendar-'+day.fecha+'-1'"><a :href="'#' + day.fecha" class="event-name-calendar">@{{year_events[day.fecha]?.data[1]?.name | txt_event}}</a></span>
                                        </div>

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
    <div class="carepoint-subtitle mb-3" v-if="type == 3">@{{months_of_the_year[parseInt(mm)]['name']}} @{{yyyy}}</div>
    <div class="carepoint-subtitle mb-3" v-else-if="type == 2">Week, @{{calendar_week[0]['mes']}} @{{calendar_week[0]['dia']}} to <span v-if="calendar_week[1]['mes'] != calendar_week[6]['mes']"> @{{calendar_week[6]['mes']}}</span> @{{calendar_week[6]['dia']}}</div>
    <div class="carepoint-subtitle mb-3" v-else>Today</div>

    <div class="loading-events w-100 text-center d-none">
        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading events...
    </div>

    <div v-if="events.length > 0">
    <template v-for="event in events" >
        <div class="card mb-3 shadow-sm" :id="event.date">
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
                                            <p class="text-muted name-event-subtitle">@{{day.location}}</p>
                                        </div>
                                        <div class="widget team col-12 col-lg-6 p-0">
                                            <div class="d-flex">
                                                <div class="pl-0 pl-md-3 pl-lg-3 avatar-imgs ml-0 ml-lg-0 ml-lg-auto">
                                                    <p>
                                                        <span class="btn btn-link" v-on:click="eventDetails(day.id)" style="text-decoration: none;">
                                                            <template v-for="member in day.members">
                                                                <img :src="member.user.photo" class="member-img" :title="member.user.name + ' ' + member.user.lastname" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                                            </template>
                                                            <i class="num-messages">@{{day.count_messages}}</i> <img src="{{asset('images/IconMessages.png')}}" alt="" id="icon-messages">
                                                        </span>
                                                        <a href="" class="text-danger " v-on:click.prevent="deleteEvent(day)">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </p>
                                                        <!-- <ul class="list-group list-group-horizontal"> -->
                                                                <!-- <span class="btn btn-link" v-on:click="eventDetails(day.id)" style="text-decoration: none;"> -->
                                                                    <!-- <li v-on:click="eventDetails(day.id)" style="text-decoration: none;" class="list-group-item lgi-carehub text-center" v-for="member in day.members" >
                                                                        <img :src="member.user.photo" class="member-img" :title="member.user.name + ' ' + member.user.lastname" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                                                        <span class="member-name-event">@{{member.user.name}}</span>
                                                                    </li> -->
                                                                <!-- </span> -->
                                                                <!-- <li class="list-group-item lgi-carehub text-center num-messages">
                                                                    @{{day.count_messages}} <img src="{{asset('images/IconMessages.png')}}" alt="" id="icon-messages">
                                                                </li> -->
                                                                <!-- <li class="list-group-item lgi-carehub text-center">
                                                                    <a href="" class="text-danger " v-on:click.prevent="deleteEvent(day)">
                                                                        <i class="fa fa-trash">  </i>
                                                                    </a>
                                                                </li> -->
                                                            <!-- </ul> -->
                                                            <!-- <template v-for="member in day.members">
                                                                <img :src="member.user.photo" class="member-img" :title="member.user.name + ' ' + member.user.lastname" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                                                <span class="member-name-event">@{{member.user.name}}</span>
                                                            </template> -->
                                                            <!-- @{{day.count_messages}} <i class="fa fa-comments" style="font-size:15px;"></i> --> 
                                                        <!-- <a href="" class="text-danger " v-on:click.prevent="deleteEvent(day)">
                                                            <i class="fa fa-trash"></i>
                                                        </a-->
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

</div>




@endsection

@push('scripts')

<script>
    $("main").removeClass("py-4").addClass("py-0");
    const carehub = new Vue({
        el: '#carehub',
        created: function() {
            this.refreshWidgets('{{$loveone->slug}}','{{$to_day->format("Y-m-d")}}');
            this.valButtons();
            
        },
        filters:{
            txt_event: function(value) {
                if (!value) return ''
                value = value.substr(1,6);
                return '• ' + value + '...';
            },
        },
        data: {
            months_of_the_year: @json($months), // list months
            current_slug: '',
            date_month_calendar:'', //calendario
            date_events: '',
            //fechas que indicaran el rango de eventos a trer de la bd
            date_ini_month_events: '{{$to_day->format("Y-m")."-01"}}',
            date_end_month_events: '{{$to_day->format("Y-m-t")}}',
            date_ini_week_events:'',
            date_end_week_events:'',
            date_day_events:'{{$to_day->format("Y-m-d")}}', 
            date_selected:'',

            dd: '',
            mm: '',
            yyyy:'',
            calendar_month:'',
            calendar_week:'',
            month_name: '',
            current_year: '',
            current_month_number: parseInt('{{date("n")}}'),
            //calendar: '',
           // week_div: '',
           // day_div: '',
            type: 1,
            limit_buttons_ini: moment().subtract('years', 25),
            limit_buttons: moment(),

            events:[],
            year_events:[],
            month_events:[],
            week_events:[],
            day_events:[],
            events_in_calendar: [],
        },
        methods: {
            refreshWidgets: function(current_slug,date) {
                this.current_slug = current_slug;
                this.date_events = date;
                this.date_month_calendar = date;
                this.calendar = '';                
                this.dd = '';
                this.mm = '{{date("m")}}';
                this.yyyy = '{{date("Y")}}';
                this.current_year = '{{date("Y")}}';
                this.now = date;
                this.getCalendar();
                this.listMonths();
                this.getYearEvents();
               // this.filterEvents();
               //this.getEventsInCalendar();
                this.events = this.day_events;

            },
            listMonths: function(){
                if(this.yyyy == this.current_year){
                    $.each(this.months_of_the_year,function(index, month){
                        var current_month_number = parseInt('{{date("n")}}');
                        if(index <= current_month_number){
                            $("#"+month['m']).attr('disabled',false);
                        }else{
                            $("#"+month['m']).attr('disabled',true);
                        }
                    });

                    if(this.mm > this.current_month_number){
                        this.mm = '01';
                    }
                }else{
                    $("#monthsSelect .op").attr('disabled',false);
                }
            },
            calendarType: function(type) {
                //alert(type);
                this.type = type;
                if (type == 1) {
                    
                    $("#Today").addClass("btn-outline-pink-active").removeClass("disabled");
                    $("#Week, #Month").removeClass("btn-outline-pink-active");
                    this.events = this.day_events;
                    //this.getEvents();
                } else if (type == 2) {
                   
                    $("#Week").addClass("btn-outline-pink-active").removeClass("disabled");
                    $("#Today, #Month").removeClass("btn-outline-pink-active");
                    this.events = this.week_events;
                    //this.getEvents();

                } else if (type == 3) {
                    
                    $("#Month").addClass("btn-outline-pink-active").removeClass("disabled");
                    $("#Today, #Week").removeClass("btn-outline-pink-active");
                    this.events = this.month_events;
                    if(this.date_events_month == ''){
                        //this.getEvents();
                    }else{
                        //this.searchEvents();
                    }
                } else {
                    alert();
                }
                
                
            },
            getCalendar: function() {


               // $('#calendar').hide();
                $('#calendar_div .loading').show();

                var url = '{{ route("carehub.getCalendar", ["*DATE*"]) }}';
                url = url.replace('*DATE*', this.date_month_calendar);

                axios.get(url).then(response => {
                   // this.calendar = response.data.calendar;
                  // console.log(response.data);
                    this.calendar_month = response.data.calendar;
                    this.month_name = response.data.month;
                    if(this.calendar_week == ''){
                        this.calendar_week = response.data.week;
                    }
                    
                   // this.week_div = response.data.week;
                   // this.day_div = response.data.day;
                    $('#calendar_div .loading').hide();
                //    $('#calendar').show();
                   // this.eventInCalendar();
                    
                }).catch(error => {

                    msg = 'There was an error getting calendar. Please reload the page';
                    swal('Error', msg, 'error');
                });
            },
            changeSelectCalendar: function(){
                this.date_month_calendar = this.yyyy + "-" + this.mm + "-01";
                this.date_ini_month_events = this.yyyy + "-" + this.mm + "-01";
                var end_date = moment(this.yyyy + "-" + this.mm + "-01").add(1, 'months');
                //console.log("end of date");
                //console.log(end_date.format('YYYY-MM-DD'));
                this.date_end_month_events = end_date.format('YYYY-MM-DD'),
                this.listMonths(); // modificar la lista de meses
                this.getCalendar(); // modificar el calendario
                this.valButtons();
                this.filterEvents();
            },
            changeSelectCalendarYear: function(){
                this.getYearEvents();
                this.changeSelectCalendar();
            },
            valButtons: function(){
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
            datePrev:function(){
                //console.log(this.mm);
                if(this.mm == '01'){
                    this.yyyy = parseInt(this.yyyy) - 1;
                    this.mm = '12';
                    this.getYearEvents();
                }else{
                    var m = parseInt(this.mm) - 1;
                    if(m < 10){
                        this.mm = '0' + m;
                    }else{
                        this.mm = m;
                    }
                }
                this.valButtons();
                //this.getEvents();
                this.changeSelectCalendar();
            },
            dateNext:function(){
                if(this.mm == '12'){
                    this.mm = '01';
                    this.yyyy = parseInt(this.yyyy) + 1;
                    this.getYearEvents();
                }else{
                    var m = parseInt(this.mm) + 1;
                    if(m < 10){
                        this.mm = '0' + m;
                    }else{
                        this.mm = m;
                    }
                }
                this.valButtons();
                //this.getEvents();
                this.changeSelectCalendar();
            },

            getYearEvents: function(){
                // $('.events .card-events-date').hide();
                $('.loading-events').show();
                var url = '{{ route("carehub.getEvents", ["*SLUG*","*DATE*","*TYPE*"]) }}';
                url = url.replace('*SLUG*', this.current_slug);
                //url = url.replace('*DATE*', this.date_events);
                url = url.replace('*DATE*', this.yyyy);
                url = url.replace('*TYPE*', this.type);
                axios.get(url).then(response => {

                if (response.data.success) {
                    this.year_events = response.data.data.events;
                    // if(this.month_events){
                         this.filterEvents();
                    // }
                    // this.date_title = response.data.data.date_title;
                    //this.count_event = this.events.length;
                    // this.eventInCalendar();
                } else {

                }

                 $('.loading-events').hide();
                // $('.events .card-events-date').show();

                }).catch(error => {

                msg = 'There was an error getting events. Please reload the page';
                swal('Error', msg, 'error');
                });
            },
            filterEvents: function(){
                //console.log(this.year_events);
                this.month_events = [];
                Object.entries(this.year_events).forEach(([key, event]) => {
                //alert();
                   // console.log('filtro');
                    //console.log(event.date + "  " + carehub.date_ini_month_events + "  " + carehub.date_end_month_events);
                    if(event.date >= carehub.date_ini_month_events && event.date <= carehub.date_end_month_events){
                        carehub.month_events.push(event);
                        //console.log(event.data);
                        
                    }

                    if(carehub.week_events.length == 0){
                        if(event.date >= carehub.calendar_week[0]['fecha'] && event.date <= carehub.calendar_week[6]['fecha']){
                            carehub.week_events.push(event);
                        }   
                    }

                    if(carehub.date_day_events.length == 0){
                        if(event.date == carehub.date_day_events){
                            carehub.day_events.push(event);
                        }    
                    }
                });

                if (this.type == 1) {
                    this.events = this.day_events;
                    
                } else if (this.type == 2) {
                    this.events = this.week_events;
                    
                } else if (this.type == 3) {
                    this.events = this.month_events; 
                }

                this.getEventsInCalendar();
            },
            getEventsInCalendar: function(){
                // $(".eventname").html('-');
                // $(".eventpoint").html('-');
                Object.entries(this.month_events).forEach(([key, event]) => {
                //alert();
                   // console.log('filtro');
                    console.log(event.date + "  " + carehub.date_ini_month_events + "  " + carehub.date_end_month_events);
                    //console.log(event.data);

                    // asosiativo['' + event.date + ''] = event.data[0]['name'];
                    //console.log(asosiativo);
                    //var  c = { event.date : { event.data[0]['name'] }};
                   // $(".event-calendar-"+event.date+"-0").html("• " + event.data[0]['name'] );
                    //carehub.events_in_calendar.push({ 'event.date' : { event.data[0]['name'] }});
                    //carehub.events_in_calendar.push(event.data[0]['name']);
                    //carehub.events_in_calendar[""+event.date+""].push(event.data[0]['name']);
                     //console.log(event.date + "  " + event.data[0]['name']);
                    $(".event-calendar-"+event.date+"-point").html("•");
                    if(event.data[1] != undefined){
                        //carehub.events_in_calendar[event.date].push(event.data[1]['name']);
                        $(".event-calendar-"+event.date+"-1").html("• " + event.data[1]['name'] );
                    }
                    

                    
                });
            },
            
            

            // searchCalendar: function() {
            //     $('#calendar').hide();
            //     $('#calendar_div .loading').show();

            //     this.date_events_month = this.yyyy + "-" + this.mm + "-01";

            //     var url = '{{ route("carehub.getCalendar", ["*DATE*"]) }}';
            //     url = url.replace('*DATE*', this.date_events_month);
            //     axios.get(url).then(response => {
            //         this.calendar_month = response.data.calendar;
            //         this.month_name = response.data.month;
            //        // this.week_div = response.data.week;
            //        // this.day_div = response.data.day;
            //         $('#calendar_div .loading').hide();
            //         $('#calendar').show();
            //         this.valButtons();
            //         this.eventInCalendar();
            //     }).catch(error => {

            //         msg = 'There was an error getting calendar. Please reload the page';
            //         swal('Error', msg, 'error');
            //     });

            // },
        }
    });

   
</script>

@endpush