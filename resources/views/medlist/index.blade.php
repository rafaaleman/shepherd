@extends('layouts.app')

@section('content')
<div class="container" id="medlist">
    <div class="row mb-3 align-items-center justify-content-center">
        <div class="col-sm-12 col-md-8 col-lg-8 card row" style="word-wrap: normal">
            <div class="row align-items-center justify-content-center " id="calendar_div">
                <div class="loading text-center w-100">
                    <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading calendar...
                </div>
                <div class="col-md-12 row" id="day_div">
                    <template v-for="day in day_div">

                        <div class="col text-center col-day" :class="day.class">
                            <div class="">
                                <div v-if="now == day.dia" class="rounded-circle align-self-center box-now" :class="day.fecha">
                                    <small>@{{ day.dia_nom  }}</small>
                                    @{{ day.dia  }} <br>
                                </div>
                                <div v-else class="box-day align-self-center">
                                    <small>@{{ day.dia_nom  }}</small>
                                    @{{ day.dia  }} <br>
                                </div>
                            </div>

                        </div>


                    </template>
                </div>
               
                
            </div>
        </div>
        <div class="col-4 d-none d-sm-none d-lg-block">
            @if ($is_admin)
            <a href="{{route('medlist.form.create',[$loveone->slug])}}" class="float-right btn  btn-primary btn-lg  rounded-pill text-white">
                Add New Medication
            </a>
            @endif
        </div>
    </div>

    


    <div class="loading-medlist w-100 text-center">
        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading medications...
    </div>
    <div v-if="medlist.length > 0">
        <template v-for="(medicine,key) in medlist">
        
        <div class="row col-12 justify-content-center align-items-center mb-2 row-medlist" style="padding: 0;">
                <div class="-AM col-2 col-sm-1 text-uppercase time justify-content-center align-items-center text-right" v-if="medicine.frequency != 'as needed'">
                    <b>@{{medicine.time_cad_gi}}</b> <br>
                    @{{medicine.time_cad_a}}
                </div>
                <div class="-AM col-2 col-sm-1 text-uppercase time justify-content-center align-items-center text-right" v-else>
                    <b>As needed</b> <br>
                    
                </div>
                <div class="col-10 col-sm-11 m-0 card">
                    
                    <div class="card-body row row-medlist">
                        <div class="col-xsm col-3 col-sm-2 col-md-2 col-lg-1">
                            <template v-if="medicine.route == 'Intravenous'">
                                <img src="{{asset('/img/icons/injection.png')}}" alt="dosage" class="dosage">
                            </template>
                            <template v-else>
                                <img src="{{asset('/img/icons/tablets.png')}}" alt="dosage" class="dosage">
                            </template>
                        </div>
                        <div class="col-xsm col-6 col-sm-8 col-md-8 col-lg-10">
                            <a href="#!" data-toggle="modal" data-target="#api-medlist-modal" class=""  @click.prevent="wikiMedication(medicine)">
                                
                                <h5 class="font-weight-bold Lipitor mb-3" >@{{medicine.medicine}}</h5>
                            </a>
                            <a href="" data-toggle="modal" data-target="#medlist-modal" class="" @click.prevent="viewMedication(medicine,key)" v-if="medicine.frequency != 'as needed'">
                                <i class="fa fa-calendar" style="font-size:20px;color:#cdcdd8"></i> Treatment Schedule
                            </a>
                            <div class="role">@{{medicine.prescribing}}s</div>
                        </div>
                        <div class="col-xsm col-3 col-sm-2 col-md-2 col-lg-1 justify-content-center align-items-center text-right" v-if="medicine.frequency != 'as needed'">
                            <template v-if="medicine.status == 1">
                                <i class="fa fa-check-circle" style="font-size:40px;color:#369bb6;"></i>
                            </template>
                            <template v-else>
                                <i class="fa fa-check-circle-o" style="font-size:40px;color:#cdcdd8"></i>
                            </template>
                        
                        </div>
                    </div>
                </div>
                            
            </div>

        </template>
    </div>
    <div v-else class=" w-100 text-center">No medications found...</div>
    <br><br>
    <center>
        <div class=" d-block d-sm-block d-lg-none mb-3">
            @if ($is_admin)
                <a href="{{route('medlist.form.create',[$loveone->slug])}}" class="btn  btn-primary btn-lg  rounded-pill text-white">
                    Add New Medication
                </a>
            @endif
        </div>
    </center>
 
    
    
@include('medlist.medlist_modal')
</div>

@endsection
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .Lipitor {
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.19;
        letter-spacing: normal;
        text-align: left;
        color: #343434;
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

    .dosage{
        width:50px;
    }

    .-AM {
        /* font-family: Gotham; */
        font-size: 15px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.2;
        letter-spacing: normal;
        text-align: right;
        color: #d36582;
    }

    .list-group-item {
        border-bottom: 1px solid rgba(0, 0, 0, 0.125) !important;
    }

    @media only screen and (min-width: 774px) and (max-width: 1250px) {

        #calendar_div .col-day {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        .box-event,
        .box-now {
            width: 40px;
            height: 40px;
        }

        .time {
            font-size: 1rem;
            text-align: left;
            right: 5px;
        }

 

    }

    @media only screen and (min-width: 571px) and (max-width: 773px) {

        #calendar_div .col-day {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        .box-event,
        .box-now {
            width: 40px;
            height: 40px;
        }



        .time {
            font-size: .9rem;
            text-align: left !important;
            right: 5px;
        }


    }
    @media only screen and (max-width: 570px) {

        #calendar_div .col-day {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }

        .box-event,
        .box-now {
            width: 40px;
            height: 40px;
        }

        .time {
            font-size: 1rem;
            text-align: left;
        }

        .eventinf,
        .events {
            padding-left: 5px !important;
            padding-right: 5px !important;

        }

    }

 @media only screen and (max-width: 380px) {

        #calendar_div .col-day {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }

        .box-event, .box-now {
            width: 40px;
            height: 40px;
        }

        .time {
            font-size: .8rem;
            text-align: left !important;
        }

        .card-body {
            padding-left: 5px !important;
            padding-right: 5px !important;

        }

        .col-xsm{
            padding:0;
        }

        .row-medlist{
            margin:0px;
            padding-left: 0px;
            padding-right: 0px;
        }

        .dosage{
            width: 30px;
        }

        .fa{
            font-size: 30px !important;
        }
 }
</style>

@endpush
@push('scripts')
<script>
    $("main").removeClass("py-4").addClass("py-0");
    const medlist = new Vue({
        el: '#medlist',
        created: function() {
            this.refreshWidgets({{$loveone->id}}, '{{$loveone->slug}}', '{{$to_day->format("Y-m-d")}}', '{{$to_day->format("Y-m")}}', '{{$to_day->format("d")}}');
        },
        data: {
            medlist:'',
            date: '',
            loveone_id: '',
            current_slug: '',
            date_title: '',
            calendar: '',
            medlist_complete : '',
            medication_complete : '',
            medication_view : '',
            products:''
            
        },
        methods: {
            refreshWidgets: function(loveone_id, current_slug, date, month, now) {
                this.loveone_id = loveone_id;
                this.current_slug = current_slug;
                this.date_medlist = date;
                this.month = month;
                this.now = now;
                this.day_div = '';
                this.medlist = '';
                this.medlist_complete = '';
                this.medication_complete = '';
                this.medication_view = '',
                this.getCalendar();
                this.getMedications();
            },
            
            getMedications: function() {

                //console.log("current_slug " + this.current_slug  +  ", loveone_id" + this.loveone_id +  ", date" + this.date_medlist, ", events" + this.events);
                $('.events .card-events-date').hide();
                $('.loading-medlist').show();

                var url = '{{ route("medlist.getMedications", ["*SLUG*","*DATE*"]) }}';
                url = url.replace('*SLUG*', this.current_slug);
                url = url.replace('*DATE*', this.date_medlist);
                //console.log(url);
                axios.get(url).then(response => {

                    if (response.data.success) {
                        this.medlist = response.data.data.medlist;
                        this.medlist_complete = response.data.data.medlist_modal;
                       // console.log(response.data.data.medlist_modal);
                    } 

                    $('.loading-medlist').hide();
                    $('.events .card-events-date').show();

                }).catch(error => {

                    msg = 'There was an error getting events. Please reload the page';
                    swal('Error', msg, 'error');
                });

            },
            getCalendar: function() {


               // $('#day_div').hide();
                $('#calendar_div .loading').show();

                var url = '{{ route("medlist.getCalendar", ["*DATE*"]) }}';
                url = url.replace('*DATE*', this.date_medlist);
                axios.get(url).then(response => {
                   /* this.calendar = response.data.calendar;
                    this.month_name = response.data.month;
                    this.week_div = response.data.week;*/
                    this.day_div = response.data.day_medlist;
                    $('#calendar_div .loading').hide();
                  //  $('#day_div').show();

                }).catch(error => {

                    msg = 'There was an error getting calendar. Please reload the page';
                    swal('Error', msg, 'error');
                });

            },
            wikiMedication: function(medication) {
                //console.log(medication);
                var url = '{{ route("medicine.search.wiki") }}';
                    this.medications_search = [];
                    
                    const sendPostRequest = async () => {
                        try {
                            const resp = await axios.post(url, {keyword:medication.drugbank_pcid});
                            this.products = resp.data.products;
                          //  $("#message-search").removeClass('d-none').html('Results');
                        } catch (err) {
                            // Handle Error Here
                            console.error(err);
                        }
                    };

                    sendPostRequest();
              
            },
            viewMedication: function(medication,key) {
                
                this.medication_view = medication;
                this.medication_view.key = key;
                //console.log(this.medlist_complete[medication.medication_id]);
                this.medication_complete = this.medlist_complete[medication.medication_id];
            },
            deleteMedication: function(medication){
                swal({
                    title: "Warning",
                    text: "Are you sure to eliminate the " + medication.time_cad_gi + " " +  medication.time_cad_a + " drug intake on " + medication.date_usa +"?",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        "Yes, I'm sure!"
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {

                    //console.log(isConfirm);
                    if (isConfirm) {
                        console.log(this.medication[this.medication_view.skey]);
                        console.log(medication);
                        medication = "";
                        
                    } else {
                    }
                });
            },
            deleteMedlist: function(medlist,slug){
               // console.log(medlist);
                swal({
                    title: "Warning",
                    text: "Are you sure to delete the '"+medlist.medicine+"' medication?",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        "Yes, I'm sure!"
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {

                    if(isConfirm){
                        var url = '{{ route("medlist.delete") }}';
                        data = {
                            medlist: medlist,
                            slug: slug
                        };

                        axios.post(url, data).then(response => {
                            
                            if( response.data.success == true ){
                                //joinTeam.getInvitations();
                                msg = 'The medication was deleted';
                                icon = 'success';
                                swal(msg, "", icon).then((value) => {
                                    location.reload();
                                });
                            } else {
                                msg = 'There was an error. Please try again';
                                icon = 'error';
                                swal(msg, "", icon)
                            }
                            
                            
                        });
                    }
                });
            }

        },
    });
</script>
@endpush
