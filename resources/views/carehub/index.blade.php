@extends('layouts.app')

@section('content')
<div class="container"  id="carehub">
    <div class="row mb-3 align-items-center justify-content-center">
        <div class="col-sm-12 col-md-8 col-lg-8 row">
            <div class="col-4 px-2"><button type="button" v-on:click="calendarType(1)" data-tpe="1" class="btn-event btn btn-lg btn-block rounded-pill btn-outline-danger rounded-top active text-white" id="Today">Today</button></div>
            <div class="col-4 px-2"><button type="button" v-on:click="calendarType(2)" data-tpe="2" class="btn-event btn btn-lg btn-block rounded-pill btn-outline-danger rounded-top disabled" id="Week">Week</button></div>
            <div class="col-4 px-2"><button type="button" v-on:click="calendarType(3)" data-tpe="3" class="btn-event btn btn-lg btn-block rounded-pill btn-outline-danger rounded-top disabled" id="Month">Month</button></div>
        </div>
        <div class="col-4 d-none d-sm-block">
            @if ($is_admin)
                <a href="{{route('carehub.event.form.create',[$loveone->slug])}}" class="float-right btn btn-info rounded-pill font-weight-bold text-white">
                    <div class="member d-flex add">
                        <i class="fas fa-plus-circle fa-3x mr-2"></i>
                        <div class="data">
                            <div class="name mt-2">Add New Event</div>
                        </div>
                    </div>
                </a>
            @endif
        </div>
    </div>

    <div class="row align-items-center justify-content-center px-3" id="calendar_div">
        <div class="loading text-center w-100">
            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading calendar...
        </div>
        <div class="col-md-12 row" id="day_div">
                <template v-for="day in day_div">
                    
                    <div class="col col-md-1 text-center col-day" :class="day.class" id="day.fecha">
                        <div v-if="month_name == day.mes" class="pb-3">
                            <div v-if="now == day.dia" class="rounded-circle align-self-center box-now">
                                @{{ day.dia  }} <br>
                                <small>@{{ day.mes  }}</small>
                            </div>
                            <div v-else class="box-day align-self-center">
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
                        <div class="col col-md-1 text-center col-day bg-rose day_month" :class="day.class" id="day.fecha">
                                <template>
                                    <div  class="align-self-center week-box">
                                        @{{ day.dia  }} <br>
                                        <small>@{{ day.mes  }}</small>
                                    </div>
                                </template>
                        </div>
                    </template>
                    <template v-else>
                        <div class="col col-md-1 text-center col-day day_month_web" :class="day.class" id="day.fecha">
                                <template>                                    
                                    <div  class="box-day">
                                        @{{ day.dia  }} <br>
                                        <small>@{{ day.mes  }}</small>
                                    </div>
                                </template>
                        </div>
                    </template>
                </template>
        </div>
        <div class="col-md-12 d-none" id="month_div">
            <template v-for="week in calendar">
                <div class="row border-bottom-1 mb-3">
                    <template v-for="day in week.datos">
                        <div class="col text-center col-day" id="day.fecha">
                            <div v-if="month_name == day.mes" class="pb-3">
                                <div v-if="now == day.dia" class="rounded-circle align-self-center box-now">
                                        @{{ day.dia  }} <br>
                                        <small>@{{ day.mes  }}</small>
                                </div>
                                <div v-else class="box-day">
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


    <div class="loading-events w-100 text-center">
        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading events...
    </div>
    <template  v-for="event in events">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 events">
                        <div class="pl-3 avatar-imgs">
                            <h6 class="card-title font-weight-bold mb-5">@{{event.title}}</h6>
                            <template v-for="(day,index) in event.data">
                                        <div class="row border-bottom-1 mb-3">
                                            <div class="col-3 col-sm-1 col-lg-2">
                                                <div class="bottom-50 end-50">
                                                    <h3 class="text-danger font-weight-bold text-uppercase" style="line-height:.7">
                                                    @{{day.time_cad_gi}} <br/><small style="font-size:.6em">@{{day.time_cad_a}}</small>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div class="col-9 col-sm-11 col-lg-10 row" style="line-height:.7">
                                                <div class="col-12 col-lg-10">
                                                    <h5 class="font-weight-bold text-truncate" >@{{day.name}}</h5>
                                                    <p class="text-muted" >@{{day.location}}</p>
                                                </div>
                                                <div class="col-12 col-lg-2">
                                                    <div>
                                                        <i class="fa fa-comments" style="font-size:25px;"></i>

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
@endsection
@push('styles')
<style>
    .col-day{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .box-day{
        color :#cdcdd8;
        line-height: 1;
        font-size: 18px;
        line-height:1.3;
        display: flex;
        justify-content: center;
        align-content: center;
        flex-direction: column;
    }
    .box-now{
        width: 50px;
        height: 50px;
        box-shadow: 0 5px 15px 0 rgba(94, 102, 137, 0.2);
        background-color: #d36582;
        color:#fff;
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
    .box-day small, .box-now small, .week-box small{
        font-size: 8px;
        text-transform: uppercase;
        font-weight: 400;

    }

    .day_month, .day_month_web{
        padding: 10px 0;
    }

    .day_month .week-box{
        background-color: #d36582;
        color:#fff;
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

    .bg-rose{
        background: #d36582;
    }

    .day_month:nth-child(4){
        border-radius: 50px 0px 0px 50px !important;
    }

    .day_month:nth-child(10){
        border-radius: 0px 50px 50px 0px !important;
    }
    
</style>

@endpush
@push('scripts')
<script>
    $("main").removeClass("py-4").addClass("py-0");
    const carehub = new Vue ({
        el: '#carehub',
        created: function() {
            this.refreshWidgets({{$loveone->id}}, '{{$loveone->slug}}','{{$to_day->format("Y-m-d")}}','{{$to_day->format("Y-m")}}','{{$to_day->format("d")}}');
        },
        data: {
            type : 1,
            date : '',
            events: '',
            loveone_id : '',
            current_slug : '',
            current_members: '',
            careteam_url: '',
            date_title: '',
            calendar: '',
            week_div: '',
            day_div:'',
            month:'',
            month_name : '',
            now: ''
        },
        filters: {
        },
        computed:{ 
        },
        methods: {
            refreshWidgets: function( loveone_id, current_slug, date,month,now){
                this.loveone_id = loveone_id;
                this.current_slug = current_slug;
                this.date_events = date;
                this.month = month;
                this.now = now;
                this.events = '';
                this.calendar = '';
                this.week_div = '';
                this.day_div = '';
                this.getEvents();
                this.getCalendar();
            },
            calendarType:function(type){
                //alert(type);
                this.type = type;
                if(type == 1 ){
                    $("#day_div").removeClass("d-none");
                    $("#week_div, #month_div").addClass("d-none");
                    $("#Today").addClass("active text-white").removeClass("disabled");
                    $("#Week, #Month").removeClass("active text-white");
                }else if(type == 2 ){
                    $("#week_div").removeClass("d-none");
                    $("#day_div, #month_div").addClass("d-none");
                    $("#Week").addClass("active text-white").removeClass("disabled");
                    $("#Today, #Month").removeClass("active text-white");
                }else if(type == 3 ){
                    $("#month_div").removeClass("d-none");
                    $("#day_div, #week_div").addClass("d-none");
                    $("#Month").addClass("active text-white").removeClass("disabled");
                    $("#Today, #Week").removeClass("active text-white");
                }else{
                    alert();
                }
                this.getEvents();
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
                   
                    if(response.data.success){
                        this.events = response.data.data.events_to_day;
                        this.date_title = response.data.data.date_title;
                    } else {
                        
                    }
                    
                $('.loading-events').hide();
                $('.events .card-events-date').show();
                    
                }).catch( error => {
                    
                    msg = 'There was an error getting events. Please reload the page';
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
                    this.month_name = response.data.month;
                    this.week_div = response.data.week;
                    this.day_div = response.data.day;
                $('#calendar_div .loading').hide();
                $('#calendar').show();
                    
                }).catch( error => {
                    
                    msg = 'There was an error getting calendar. Please reload the page';
                    swal('Error', msg, 'error');
                });
           
            },
            
        },
    });

   
</script>
@endpush