@extends('layouts.app')

@section('content')
<div class="container" id="create_medication">

    <form method="POST" action="#" style="width: 100%;" class="row mx-0 fs-0 justify-content-center" v-on:submit.prevent="createMedication()">
        @csrf
        
        <div class="col-md-12">
            <h3>Add medication</h3>
        </div>

        
        <div class="w-100">

                <div class="card  col-12 col-lg-12 my-4 shadow-sm">
                    <div class="card-body row">
                    <div class="input-group">
                        <div class="input-group-append w-100">
                            <input type="text" autocomplete="off" class="form-control only-border-bottom"  id="medicine" name="medicine" value="" placeholder="Medicine" required v-model="medication.medicine">

                        </div>    

                        {{--<div class="input-group-append w-100">
                            <input type="text" autocomplete="off" class="form-control only-border-bottom" @keyup.prevent="seachMedication()" data-toggle="dropdown"  aria-label="Text input with dropdown button" id="medicine" name="medicine" value="" placeholder="Medicine" required v-model="medication.medicine">

                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#!" id="message-dropdown">Please enter @{{count_chatacters }} or more characters</a>
                                <a class="dropdown-item" href="#!" class="d-none" id="message-search"></a>
                                <div role="separator" class="dropdown-divider"></div>
                                <template v-for="medicine in medications_search">
                                    <a class="dropdown-item w-100" href="#" @click.prevent="selectMedicine(medicine)"><span v-html="medicine.hit" ></span> </a>
                                </template>
                                
                            </div>
                        </div> --}}
                    </div>
                    </div>
                </div>

                <div class="card  col-12 col-lg-12 mt-4 mb-2 shadow-sm">
                    <div class="card-body row">
                        <div class="input-group">
                            <div class="input-group-append w-100">
                                <input type="text" autocomplete="off" class="form-control only-border-bottom"  id="prescribing" name="prescribing" value="" placeholder="Prescribing doctor" required v-model="medication.prescribing">

                            </div>    
                        </div>
                    </div>
                </div>

            <div class="row">
                <!--div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 my-2 accordion" id="routeAcordion">
                    <div class="card my-2 shadow-sm">
                        <div class="card-body" id="headingRoute">
                            <h2 class="mb-0 toggle-data">
                                <button class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapseRoute" aria-expanded="true" aria-controls="collapseRoute">
                                    Route @{{ medication.route }}
                                </button>
                            </h2>
                        </div>

                        
                        <div id="collapseRoute" class="collapse"  aria-labelledby="headingRoute" data-parent="#routeAcordion">
                            <div class="card-body">
                                <div class="form-group row mx-1" id="careteam">
                                    <div class="col-md-12 members px-0" id="" class="collapse" aria-labelledby="headingRoute" data-parent="#careteam">
                                        <template v-for="route in routes">
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>@{{route.route}}</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                            {{--<input type="radio" v-bind:value="route.route" @click="selectRoute(route)" v-model="medication.route" >--}}
                                                            <input type="radio" v-bind:value="route.route" v-model="medication.route" >
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                   
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div-->


                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 my-2 accordion" id="dosageAcordion">
                    <div class="card my-2 shadow-sm">
                        <div class="card-body" id="headingDosage">
                            <h2 class="mb-0 toggle-data">
                                <button class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapseDosage" aria-expanded="true" aria-controls="collapseDosage" id="btncollapseDosage">
                                    Dosage @{{ medication.dosage }}
                                </button>
                            </h2>
                        </div>

                        <div id="collapseDosage" class="collapse"  aria-labelledby="headingDosage" data-parent="#dosageAcordion">
                            <div class="card-body">
                                <div class="form-group row mx-1" id="careteam">
                                    <div class="col-md-12 members px-0" id="" class="collapse" aria-labelledby="headingDosage" data-parent="#careteam">
                                        <template v-for="dosage in dosages">
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>@{{dosage.dosage}}</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                            <input type="radio" v-bind:value="dosage.dosage" @click="selectDosage()" v-model="medication.dosage">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                   
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 my-2 accordion" id="frequencyAcordion">
                    <div class="card my-2 shadow-sm">
                        <div class="card-body" id="headingfrequency">
                            <h2 class="mb-0 toggle-data">
                                <button class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapsefrequency" aria-expanded="true" aria-controls="collapsefrequency" id="btncollapsefrequency">
                                    Frequency  @{{ medication.frequency }}
                                </button>
                            </h2>
                        </div>

                        <div id="collapsefrequency" class="collapse" aria-labelledby="headingfrequency" data-parent="#frequencyAcordion">
                            <div class="card-body">
                                <div class="form-group row mx-1" id="careteam">
                                    <div class="col-md-12 members px-0" id="" class="collapse" aria-labelledby="headingfrequency" data-parent="#careteam">
                                        <template>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>6x per day (Every 4 hrs)</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="4" @click="selectFrequency(4)" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>4x per day (Every 6 hrs)</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="6" @click="selectFrequency(6)" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>3x per day (Every 8 hrs)</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="8" @click="selectFrequency(8)" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>2x per day (Every 12 hrs)</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="12" @click="selectFrequency(12)" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>1x per day (Every 24 hrs)</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="24" @click="selectFrequency(24)" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>As needed</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="as needed" @click="selectFrequency('needed')" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </template>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>





            <div class="row">
                 
                <div class="col-12 col-sm-12 col-lg-6 col-xl-6 my-2">
                    <div class="card shadow-sm my-2 col-12">
                        <div class="card-body">

                            <input id="time" type="text" class="form-control no-border" name="time" value="" required v-model="medication.time" min="{{ $date_now->format('g:i a') }}" autocomplete="off" placeholder="First Dose">

                        </div>
                    </div>
                </div>
                

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 my-2 accordion" id="daysAcordion">
                    <div class="card my-2 shadow-sm">
                        <div class="card-body" id="headingDays">
                            <h2 class="mb-0 toggle-data">
                                <button class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapseDays" aria-expanded="true" aria-controls="collapseDays" id="btncollapseDays">
                                    Days of duration @{{ medication.days }}
                                </button>
                            </h2>
                        </div>

                        <div id="collapseDays" class="collapse" aria-labelledby="headingDays" data-parent="#daysAcordion" >
                            <div class="card-body">
                                <div class="form-group row mx-1" id="careteam">
                                    <div class="col-md-12 members px-0" id="" class="collapse" aria-labelledby="headingDays" data-parent="#careteam">
                                        <template>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>1 day</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="1" v-model="medication.days" @click="selectDays()">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                            @for($i=2; $i < 11; $i++)
                                                <div class="member w-100 ml-0 mb-2">
                                                    <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>{{$i}} days</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <inp<input type="radio" value="{{$i}}" v-model="medication.days" @click="selectDays()">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                    
                                                </div>
                                            @endfor
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>15 days</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="15" v-model="medication.days" @click="selectDays()">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>30 days</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="30" v-model="medication.days" @click="selectDays()">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                           
                                        </template>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div> 

            </div>

            <div class="row">
                <div class="col-12 col-sm-12 col-lg-6 col-xl-6 my-2">
                    <div class="card shadow-sm my-2 col-12">
                        <div class="card-body">
                            <input id="refill_date" type="date" class="form-control no-border" required name="refill_date" autocomplete="date"  placeholder="Refill Date" v-model="medication.refill_date" min="{{ $date_now->format('Y-m-d') }}" format="Y-m-d" >
                        </div>
                    </div>
                </div>
               
            </div>


            <div class="card shadow-sm my-2 col-12">
                <div class="card-body row">
                    <textarea id="notes" rows="7" type="date" class="form-control no-border no-focus" name="notes" autocomplete="notes" v-model="medication.notes" placeholder="Notes" maxlength="500"></textarea>
                </div>
            </div>



            <div class="form-group row mb-0">
                <div class="col-md-12 mt-4 mb-4 justify-content-center">
                    <center>
                        <button class="btn btn-primary loadingBtn btn-lg" type="submit" data-loading-text="Saving..." id="saveBtn">
                            Save
                        </button>
                    </center>
                    <input type="hidden" name="id" v-model="medication.id" value="">
                    <input type="hidden" name="loveone_id" v-model="medication.loveone_id"> 
                </div>
            </div>
        </div>


    </form>
</div>
@endsection

@push('styles')
<link href="{{asset('css/iconos_datepicker.css')}}" rel="stylesheet">
<link href="{{asset('css/bootstrap-datetimepicker.css')}}" rel="stylesheet">
<style>
input[type=date] {
  text-align: right;
}

input[type="date"]:before {
  content: attr(placeholder) !important;
  margin-right: 0.5em;
}


/* The customcheck */
.ccheck{
    padding-left: 0px;
}
.customcheck {
   /* display: block;*/
    position: relative;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default checkbox */
.customcheck input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom checkbox */
.checkmark {
    position: absolute;
    top: 0;
    right: 0;
    height: 25px;
    width: 25px;
    background-color: #fff;
    border-radius: 25px;
    border: 1px solid;

}

/* On mouse-over, add a grey background color */
.customcheck:hover input ~ .checkmark {
    background-color: #369bb6;
}

/* When the checkbox is checked, add a blue background */
.customcheck input:checked ~ .checkmark {
    background-color: #369bb6;
    border-radius: 25px;
    border:0;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.customcheck input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.customcheck .checkmark:after {
    left: 10px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
.no-border{
    border:0;
}
.no-focus:focus { outline: none; } 
.label{
    opacity: 0.5;
    color: #78849e;
    font-size: 10px;
    margin-bottom:0;
    padding-left: 11px;
}

.only-border-bottom{
    border: 0px ;
    border-bottom: solid 1px #cdcdd8;
    border-radius:0.25rem 0.25rem 0 0;
}

 .dropdown-toggle::after{
    float:right;
    margin-top: .5em;
}
/*
.dropdown-menu{
    height: 500%;
    overflow-y: auto;
}
*/
.dropdown-item{
    white-space: break-spaces !important;
    color:#369bb6 !important;

   
    padding: 0.15rem 1rem;
    
    border: 0;
    font-size: .8rem;
}
.dropdown-item em{
    color:#235c6b !important;
}
#create_medication .dropdown-toggle{
    color: #495057 !important;
}

</style>
@endpush

@push('scripts')
<script src="{{asset('js/datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>


<script>

$(function () {


    $('#time').datetimepicker({format: 'LT',stepping: 15, widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        }});

    $('#time').on('dp.change',function(e){
        create_medication.medication.time= moment(e.date,"h:mm:ss a").format('h:mm a');
    });
});

    const create_medication = new Vue({
        el: '#create_medication',
        created: function() {
            //console.log('create_medication');
        },
        data: {
            current_slug: '{{$loveone->slug}}',
            characters_medication: 3,
            count_chatacters: 3,
            medications_search:[],
            //routes: @json($routes),
            dosages:@json($dosages),
            medication: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: "{{ $medication->id ?? 0 }}",
                loveone_id: "{{ $loveone->id ?? 0 }}",
                medicine: "{{ $medication->medicine ?? '' }}",
                dosage: "{{ $medication->dosage ?? '' }}",
                frequency : "{{ $medication->frequency ?? '' }}",
                time: "{{ $medication->time ?? '' }}",
                days: "{{ $medication->days ?? '' }}",
                notes: "{{ $medication->notes ?? '' }}",
                refill_date: "{{ $medication->refill_date ?? '' }}",
               // route:"",
                drugbank_pcid:""
               // creator_id: "{{ $medication->creator_id ?? '' }}",
            },
        },
        filters: {},
        computed: {},
        mounted: function(){
            this.ini();
        },
        methods: {
            ini : function(){
                this.dosages.push({
                    'strengths': 'Other',
                    'dosage':  'Other'
                });
            },
          /*  seachMedication: function(){
                if(this.medication.medicine.length < this.characters_medication){
                    $("#message-search").addClass('d-none');
                    $("#message-dropdown").removeClass('d-none');
                    this.count_chatacters = (this.characters_medication - this.medication.medicine.length);
                }else{
                    $("#message-search").removeClass('d-none').html('Searching...');
                    $("#message-dropdown").addClass('d-none');
                    app.articles=[];
                    //var keyword = this.toFormData(this.medication.medicine);
                    var url = '{{ route("medicine.search") }}';
                    this.medications_search = [];
                    
                    const sendPostRequest = async () => {
                        try {
                            const resp = await axios.post(url, {keyword:this.medication.medicine});
                            this.medications_search = resp.data.medicines;
                            $("#message-search").removeClass('d-none').html('Results');
                        } catch (err) {
                            // Handle Error Here
                            console.error(err);
                        }
                    };

                    sendPostRequest();
                    
                }
            },
            selectMedicine: function(medicineSelected){
                this.medication.medicine = medicineSelected.name;
             //   this.medication.route = "";
                this.medication.dosage = "";
                this.medication.drugbank_pcid = medicineSelected.drugbank_pcid;
                var url = '{{ route("medicine.route.search") }}';
                   // this.routes = [];
                    
                    const sendMedicineRequest = async () => {
                        try {
                            const resp = await axios.post(url, {keyword:medicineSelected.drugbank_pcid});
                           // console.log(resp.data.routes);
                           // this.routes = resp.data.routes;
                            
                        } catch (err) {
                            // Handle Error Here
                            console.error(err);
                        }
                    };

                    sendMedicineRequest();
            },
            selectRoute: function(routeSelected){
                this.medication.dosage = "";
                var url = '{{ route("medicine.route.strength.search") }}';
                    this.dosages = [];
                    
                    const sendRouteRequest = async () => {
                        try {
                            const resp = await axios.post(url, {keyword:routeSelected.drugbank_pcid});
                           // console.log(resp.data.strengths);
                            this.dosages = resp.data.strengths;
                            console.log(this.dosages);
                            this.dosages.unshift({
                                'strengths': 'Other',
                                'dosage':  'Other'
                            });
                            //this.medications_search = resp.data.medicines;
                            //$("#message-search").removeClass('d-none').html('Results');
                        } catch (err) {
                            // Handle Error Here
                            console.error(err);
                        }
                    };

                    sendRouteRequest();
            },*/
            selectFrequency: function(value){
                if(value == 'needed'){
                    this.medication.time = '';
                    $("#time").attr('disabled',true);
                }else{
                    $("#time").attr('disabled',false);
                }
                $("#btncollapsefrequency").click();
            },
            selectDosage: function(){
                $("#btncollapseDosage").click();
            },
            selectDays: function(){
                
                $("#btncollapseDays").click();
            },
            createMedication: function() {
                
                
                
                if( this.medication.dosage != ""  && this.medication.frequency != ""  && this.medication.days != "" && ((this.medication.frequency != "as needed"  && this.medication.time != "") || (this.medication.frequency == "as needed"  && this.medication.time == ""))){
                    if(this.medication.time == "Invalid date"){
                        icon = 'error';
                        msg = 'Please enter a valid "Time"';
                        swal(msg, "", icon);
                        return false;
                    }
                    
                    $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                    //this.event.phone = this.event.phone.replace(/\D/g,'');
                    this.medication.time= moment(this.medication.time,"h:mm:ss a").format('HH:mm');
                    var url = '{{ route("medlist.create") }}';
                    
                    axios.post(url, this.medication).then(response => {
    
                        if (response.data.success) {
                            msg = 'Your medication was saved successfully!';
                            icon = 'success';
                            //this.medication.id = response.data.data.medication.id;

                            
                            swal({
                                title: "",
                                text: "Your medication was saved successfully!",
                                icon: "success",
                                buttons: [
                                    'Return to carehub',
                                    "Continue!"
                                ],
                            }).then((value) => {
                                //alert(value);
                                if(value){
                                    var url_medlist= '{{ route("medlist", ["*SLUG*"]) }}';
                                    url_medlist = url_medlist.replace('*SLUG*', this.current_slug);
                                    window.location =  url_medlist;
                                }else{

                                    var url_medlist= '{{ route("home") }}';
                                    window.location =  url_medlist;
                                }
                            });
                            return false;
                            
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
                }else{
                    
                    if(this.medication.dosage == ""){
                        msg = 'Please enter "Dosage"';
                        $("#collapseDosage").addClass("show");
                    }else if(this.medication.frequency == ""){
                        msg = 'Please enter "Frequency"';
                        $("#collapsefrequency").addClass("show");
                    }else if(this.medication.time == "" && this.medication.frequency != "as needed"){
                        msg = 'Please enter "Start time"';
                    }else if(this.medication.days == ""){
                        msg = 'Please enter "Days"';
                        $("#collapseDays").addClass("show");
                    }else{
                        msg = "???????";
                    }
                        icon = 'error';
                    // $('.loadingBtn').html('Save').attr('disabled', false);
                        swal(msg, "", icon);
                }
            },
            toFormData: function(obj){
                var form_data = new FormData();
                for(var key in obj){
                    form_data.append(key, obj[key]);
                }
                return form_data;
            },

        }
    });
    
    $('.collapse').on('show.bs.collapse', function () {
        var parent = $(this).data("parent");
        $(parent + " .toggle-data").addClass("dropup");
    })

    $('.collapse').on('hidden.bs.collapse', function () {
        var parent = $(this).data("parent");
        $(parent + " .toggle-data").removeClass("dropup");

    })
</script>
@endpush
