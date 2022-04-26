@extends('layouts.app9')

@section('content')
<div class="" id="medlist">
    <div class="row justify-content-center">


        <div class="col-12 col-lg-6 mb-3 pt-4 ">
            <div class="header_medlist row w-100 mb-4">
                <div class="col-12 title">
                    My Reminders
                </div>
            </div>

            <div class="row align-items-center justify-content-center mb-4 mx-0" id="calendar_div">
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

            <div class="loading-medlist w-100 text-center">
                <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading medications...
            </div>
            <div v-if="medlist.length > 0" >
                <template v-for="(medicine,key) in medlist">

                    <div class="row justify-content-center align-items-center mb-2 mx-0 row-medlist" >
                        <div class="-AM col-2 col-sm-2 text-uppercase time justify-content-center align-items-center text-right" v-if="medicine.frequency != 'as needed'">
                            <b>@{{medicine.time_cad_gi}}</b> <br>
                            @{{medicine.time_cad_a}}
                        </div>
                        <div class="-AM col-2 col-sm-2 text-uppercase time justify-content-center align-items-center text-right" v-else>
                            <b>As needed</b> <br>

                        </div>
                        <div class="col-10 col-sm-10 m-0 card">

                            <div class="card-body row row-medlist">
                                <div class="col-xsm col-3 col-sm-2 col-md-2 col-lg-1 p-0">
                                    <template v-if="medicine.route == 'Intravenous'">
                                        <img src="{{asset('/img/icons/injection.png')}}" alt="dosage" class="dosage">
                                    </template>
                                    <template v-else>
                                        <img src="{{asset('/img/icons/tablets.png')}}" alt="dosage" class="dosage">
                                    </template>
                                </div>
                                <div class="col-xsm col-6 col-sm-8 col-md-8 col-lg-9">
                                    <a href="#!" data-toggle="modal" data-target="#api-medlist-modal" class="" @click.prevent="wikiMedication(medicine)">

                                        <h5 class="font-weight-bold Lipitor mb-0">@{{medicine.medicine}}</h5>
                                    </a>
                                    
                                    <div class="medicine-prescribing">@{{medicine.prescribing}}s</div>
                                </div>
                                <div class="col-xsm col-3 col-sm-2 col-md-2 col-lg-2 justify-content-center align-items-center text-right" v-if="medicine.frequency != 'as needed'">
                                    <template v-if="medicine.status == 1">
                                        <i class="fa fa-check-circle" style="font-size:35px;color:#369bb6;"></i>
                                    </template>
                                    <template v-else>
                                        <i class="fa fa-check-circle-o" style="font-size:35px;color:#cdcdd8"></i>
                                    </template>

                                </div>
                            </div>
                        </div>

                    </div>

                </template>
            </div>
            <div v-else class=" w-100 text-center">No medications found...</div>

        </div>

        <div class="col-12 col-lg-6 bg-gray-bg mb-3 pt-4" style="min-height: 90vh;">
            <div class="header_medlist row w-100 mb-4">
                <div class="col-12 col-md-6 title">
                    My Medications
                </div>
                <div class="col-12 col-md-6 button">
                    <a href="{{route('medlist.form.create',[$loveone->slug])}}" class="float-right btn btn-primary btn-lg rounded-pill text-white mr-2 btn-carepoints">
                        Add New Medication
                    </a>
                </div>
                
            </div>

            <div class="loading-medlist w-100 text-center">
                <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading medications...
            </div>
            <div v-if="medications.length > 0">

                    <div v-for="(medication,key) in medications" class="row justify-content-center align-items-center mb-2 row-medlist" style="padding: 0;">

                        <div class="col-10 col-sm-11 m-0 card" v-if="medication.status">

                            <div class="card-body row row-medlist">
                                <div class="col-auto p-0">
                                    
                                        <img src="{{asset('/img/icons/tablets.png')}}" alt="dosage" class="dosage">
                                </div>
                                <div class="col">
                                    <div class="float-right col-icons p-0">
                                        <a href="#/" class="num-messages" style="display:inline-flex;vertical-align:sub;" v-on:click="medEdit(medication.id)">
                                            <i class="fa fa-pencil" > </i>
                                        </a>
                                            &nbsp; 
                                        <a href="#/" class="text-danger " style="display:inline-flex;vertical-align:sub;" v-on:click.prevent="deleteMedication(medication)">
                                            <i class="fa fa-trash"> </i>
                                        </a>
                                    </div>


                                    <a href="#!" data-toggle="modal" data-target="#api-medlist-modal" class="" @click.prevent="wikiMedication(medication)">

                                        <h5 class="font-weight-bold Lipitor mb-0">@{{medication.medicine}} <small class="medicine-prescribing">Refill date @{{medication.refill_date}}</small></h5>
                                    </a>
                                    <a href="" data-toggle="modal" data-target="#medlist-modal" class="medicine-prescribing" @click.prevent="viewMedication(medication,key)" v-if="medication.frequency != 'as needed'">
                                        <i class="fa fa-calendar" style="font-size:20px;color:#cdcdd8"></i> Treatment Schedule
                                    </a>
                                    <div class="medicine-prescribing">@{{medication.prescribing}}s</div>


                                    

                                </div>

                                
                            </div>
                        </div>

                    </div>

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
        </div>
    </div>

    <form action="{{route('medlist.editMedication')}}" method="post" id="formDetailMed">
        @csrf
        <input type="hidden" name="id" id="id" :value="med_edit.id">
        <input type="hidden" name="slug" :value="med_edit.slug">
    </form>






    @include('medlist.medlist_modal')
</div>

@endsection
@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
.top-bar{
    margin-bottom:0px !important;
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
            medlist: '',
            date: '',
            loveone_id: '',
            current_slug: '',
            date_title: '',
            calendar: '',
            medlist_complete: '',
            medication_complete: '',
            medication_view: '',
            products: '',
            medications:'',
            med_edit:{
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: "",
                loveone_id: "{{ $loveone->id ?? 0 }}",
                slug: "{{$loveone->slug}}"
            }
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
                this.getMedications();
                this.getCalendar();
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
                        this.medications = response.data.data.medications;
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
                        const resp = await axios.post(url, {
                            keyword: medication.drugbank_pcid
                        });
                        this.products = resp.data.products;
                        //  $("#message-search").removeClass('d-none').html('Results');
                    } catch (err) {
                        // Handle Error Here
                        console.error(err);
                    }
                };

                sendPostRequest();

            },
            viewMedication: function(medication, key) {

                this.medication_view = medication;
                this.medication_view.key = key;
                //console.log(this.medlist_complete[medication.medication_id]);
                this.medication_complete = this.medlist_complete[medication.id];
            },
            deleteMedication: function(med) {
                swal({
                    title: "Warning",
                    text: "Are you sure to eliminate the " + med.time_cad_gi + " " + med.time_cad_a + " drug intake on " + med.date_usa + "?",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        "Yes, I'm sure!"
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {

                    //console.log(isConfirm);
                    if (isConfirm) {
                        //console.log(this.med[this.med_view.skey]);
                        //console.log(med);
                        //med = "";
                        
                        var url = '{{ route("medlist.medication.delete") }}';
                        data = {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: med.id,
                        };

                        axios.post(url, data).then(response => {
                           // console.log(response.data);
                            
                            if( response.data.success == true ){
                                //joinTeam.getInvitations();
                                msg = 'The medication was deleted';
                                icon = 'success';
                                //carehub.count_medication--;
                                medlist.getMedications();
                            } else {
                                msg = 'There was an error. Please try again';
                                icon = 'error';
                            }
                            
                            swal(msg, "", icon);
                        });

                    } else {}
                });
            },
            deleteMedlist: function(medlist, slug) {
                // console.log(medlist);
                swal({
                    title: "Warning",
                    text: "Are you sure to delete the '" + medlist.medicine + "' medication?",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        "Yes, I'm sure!"
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {

                    if (isConfirm) {
                        var url = '{{ route("medlist.delete") }}';
                        data = {
                            medlist: medlist,
                            slug: slug
                        };

                        axios.post(url, data).then(response => {

                            if (response.data.success == true) {
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
            },
            medEdit: function(medlist) {
                $("#formDetailMed #id").val(medlist);
                $("#formDetailMed").submit();
                return false;
            },
          /*  deleteMedication: function(medication){
                //console.log(medication);
                swal({
                    title: "Warning",
                    text: "Are you sure to delete the '"+medication.medicine+"' medication?",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        "Yes, I'm sure!"
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {

                    if(isConfirm){
                        var url = '{{ route("medlist.medication.delete") }}';
                        data = {
                            id: medication.id,
                        };

                        axios.post(url, data).then(response => {
                           // console.log(response.data);
                            
                            if( response.data.success == true ){
                                //joinTeam.getInvitations();
                                msg = 'The medication was deleted';
                                icon = 'success';
                                //carehub.count_medication--;
                                medlist.getMedications();
                                medication.status = 0;
                            } else {
                                msg = 'There was an error. Please try again';
                                icon = 'error';
                            }
                            
                            swal(msg, "", icon);
                        });
                    }
                });
            },*/

        },
    });
</script>
@endpush