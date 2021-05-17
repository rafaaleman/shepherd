@extends('layouts.app')

@section('content')
<div class="container"  id="carehub">
  
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 events">
                            <h3 class="card-title font-weight-bold mb-3">{{$event->name}}</h3>
                            <p class="text-muted" >{{$event->location}}</p>
                            <div> Assigned </div>
                            <hr class="mb-1">
                            <div class="w-100">
                                <div class="float-left font-weight-bold vertical-align-top">{{$event->date_title}}</div>
                                <div class="float-right text-danger font-weight-bold text-uppercase">{{$event->time_cad_gi}} {{$event->time_cad_a}}</div>
                            </div>

                            <div class="bg w-100 mt-5">
                                {{$event->notes}}
                            </div>
                            
                    </div>
                </div>
            </div>
        </div>

        <div class="loading-events w-100 text-center d-none">
            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading comments...
        </div>

        @if(isset($event->comment) && $event->comment->count())

        @else
            <div class="loading-events w-100 text-center">
                <span class="spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> There are no messages...
            </div>
        @endif

        <form action="">

                <label for="name" class="col-form-label text-md-right" style="color: #78849e;font-size:10px">YOUR MESSAGE </label>
                <textarea id="mesaje" rows="7" type="date" class="form-control" name="mesaje" required autocomplete="mesaje" v-model="event.mesaje"></textarea>
                <div class=" w-100 justify-content-center my-4">
                    <center>
                        <button class="btn btn-info btn-lg rounded-pill font-weight-bold text-white">
                            Reply
                        </button>
                    </center>
                </div>
            </div>
        </form>




</div>
@endsection
@push('styles')
<style>
    .bg {
        padding: 16.5px 25px 13.1px 24px;
        background-color: #f7f7fa;
    }
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
  /*  const carehub = new Vue ({
        el: '#carehub',
        created: function() {
            console.log('carehub');
            this.refreshWidgets({{$event}});
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
                this.event = '';
               
                this.getEvent();
                //this.getCalendar();
            },
            
            getEvent: function() {
                
                //console.log("current_slug " + this.current_slug  +  ", loveone_id" + this.loveone_id +  ", date" + this.date_events, ", events" + this.events);
                
                $('.events .card-events-date').hide();   
                $('.loading-event').show();

                var url = '{{ route("carehub.getEvent", ["*EVENT*"]) }}';
                url = url.replace('*EVENT*', this.event);
                
                axios.get(url).then(response => {
                   
                    if(response.data.success){
                        this.events = response.data.data.events_to_day;
                        this.date_title = response.data.data.date_title;
                        console.log(this.events);
                        console.log("------------------------------------------");
                    } else {
                        
                    }
                    
                $('.loading-events').hide();
                $('.events .card-events-date').show();
                    
                }).catch( error => {
                    
                    msg = 'There was an error getting events. Please reload the page';
                    swal('Error', msg, 'error');
                });
           
            },
         
            
        },
    });*/

   
</script>
@endpush