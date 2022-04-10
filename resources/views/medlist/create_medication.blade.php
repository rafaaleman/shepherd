@extends('layouts.app')

@section('content')

<div class="container" id="create_medication">

    <form method="POST" action="#" style="width: 100%;" class="row mx-0 fs-0 justify-content-center" v-on:submit.prevent="createMedication()">
        @csrf
        
        <div class="col-md-12">

            <h3>{{(Request::is('medlist/medication/detail') ) ? 'Update' : 'Add'}} medication</h3>
        </div>

        
        <div class="w-100">

                <div class="card  col-12 col-lg-12 my-4 shadow-sm">
                    <div class="card-body row">
                    <div class="input-group">
                        <div class="input-group-append w-100">
                            <input data-title="Welcome to the Medlist" data-intro="You can start by typing the name of your medication here" type="text" autocomplete="off" class="form-control only-border-bottom"  id="medicine" name="medicine" value="" placeholder="Medicine" required v-model="medication.medicine">

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
                    <div class="card-body row" >
                        <div class="input-group">
                            <div class="input-group-append w-100">
                                <input data-title="Welcome to the Medlist" data-intro="Then you can enter the name of your prescribing doctor here" type="text" autocomplete="off" class="form-control only-border-bottom"  id="prescribing" name="prescribing" value="" placeholder="Prescribing doctor" required v-model="medication.prescribing">

                            </div>    
                        </div>
                    </div>
                </div>

            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 my-2 accordion" id="frequencyAcordion">
                    <div class="card my-2 shadow-sm">
                        <div class="card-body" id="headingfrequency">
                            <h2 class="mb-0 toggle-data">
                                <button  data-title="Welcome to the Medlist" data-intro="You can select how often you need to take your medication here" class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapsefrequency" aria-expanded="true" aria-controls="collapsefrequency" id="btncollapsefrequency">
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
                        
                <div class="col-12 col-sm-12 col-lg-6 col-xl-6 my-2">
                    <div class="card shadow-sm my-2 col-12">
                        <div class="card-body">
                            <input  data-title="Welcome to the Medlist" data-intro="If you know the refill date, you can add that here and we will remind you to refill your medication" id="refill_date" type="text" class="form-control no-border" name="refill_date"  placeholder="Refill Date (MM/DD/YYYY)" autocomplete="off" v-on:change="ChangeRefill()" v-model="medication.refill_date" >
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                 
                <div class="col-12 col-sm-12 col-lg-6 col-xl-6 my-2">
                    <div class="card shadow-sm my-2 col-12">
                        <div class="card-body">

                            <input  data-title="Welcome to the Medlist" data-intro="Add the time you are taking your first dose" id="time" type="text" class="form-control no-border" name="time" value="" required v-model="medication.time" min="{{ $date_now->format('g:i a') }}" autocomplete="off" placeholder="First Dose">

                        </div>
                    </div>
                </div>
                

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 my-2 accordion" id="daysAcordion">
                    <div class="card my-2 shadow-sm">
                        <div class="card-body" id="headingDays">
                            <h2 class="mb-0 toggle-data">
                                <button  data-title="Welcome to the Medlist" data-intro="Select how many days you will be taking the medication." class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapseDays" aria-expanded="true" aria-controls="collapseDays" id="btncollapseDays">
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

            <div class="card shadow-sm my-2 col-12">
                <div class="card-body row">
                    <textarea data-title="Welcome to the Medlist" data-intro="If there are any special notes you need to remember along with your medication, you can add them here" id="notes" rows="7" type="date" class="form-control no-border no-focus" name="notes" autocomplete="notes" v-model="medication.notes" placeholder="Notes" maxlength="500"></textarea>
                </div>
            </div>



            <div class="form-group row mb-0">
                <div class="col-md-12 mt-4 mb-4 justify-content-center">
                    <center>
                        <input type="checkbox" id="optout" v-model="medication.remind">
                        <label for="optout">Remind me to take this medication</label><br >
                        <button data-title="And that's it!" data-intro="Click this button to save your medication and we will remind you when it's time" class="btn btn-primary loadingBtn btn-lg" type="submit" data-loading-text="Saving..." id="saveBtn">
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/4.2.2/introjs.min.css" integrity="sha512-631ugrjzlQYCOP9P8BOLEMFspr5ooQwY3rgt8SMUa+QqtVMbY/tniEUOcABHDGjK50VExB4CNc61g5oopGqCEw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>


</style>
@endpush

@push('scripts')
<script src="{{asset('js/datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/4.2.2/intro.min.js" integrity="sha512-Q5ZL29wmQV0WWl3+QGBzOFSOwa4e8lOP/o2mYGg13sJR7u5RvnY4yq83W5+ssZ/VmzSBRVX8uGhDIpVSrLBQog==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>

    
$(function () {

    @if (!$readTour) 
        
        introJs().setOptions({
            showProgress: true,
            showButtons: true,
            showBullets: false
        }).onbeforeexit(function () {
            if( confirm("Skip this tour and don't show it again?")){
                create_medication.readTour()
            }
        }).start();
    @endif


    var refill_date = new Pikaday({
            field: document.getElementById('refill_date'),
            format: 'DD/MM/YYYY',
            minDate: moment().toDate(),
            onSelect: function() {
                
                create_medication.medication.refill_date = this.getMoment().format('YYYY/MM/DD')
            }
    });

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
            medication: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: "{{ $medication->id ?? 0 }}",
                loveone_id: "{{ $loveone->id ?? 0 }}",
                medicine: "{{ $medication->medicine ?? '' }}", 
               // dosage: "{{ $medication->dosage ?? '' }}",
                frequency : "{{ $medication->frequency ?? '' }}",
                time: "{{ $medication->time ?? '' }}",
                days: "{{ $medication->days ?? '' }}",
                notes: "{{ utf8_encode($medication->notes) ?? '' }}",
                refill_date: "{{ $medication->refill_date ?? '' }}",
               // route:"",
                drugbank_pcid:"",
                prescribing:"{{ $medication->prescribing ?? '' }}",
               // creator_id: "{{ $medication->creator_id ?? '' }}",
               remind:"{{ $medication->remind ?? false }}",
            },
        },
        filters: {},
        computed: {},
        mounted: function(){
            this.ini();
        },
        methods: {
            ini : function(){
           /*      this.dosages.push({
                    'strengths': 'Other',
                    'dosage':  'Other'
                });*/
            },/* 
           seachMedication: function(){
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
            
            selectDays: function(){
                
                $("#btncollapseDays").click();
            },
            createMedication: function() {
                
                
                
                if( this.medication.frequency != ""  && this.medication.days != "" && ((this.medication.frequency != "as needed"  && this.medication.time != "") || (this.medication.frequency == "as needed"  && this.medication.time == ""))){
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
                    /*
                    if(this.medication.refill_date == ""){
                        msg = 'Please enter "Refill Date"';
                        $("#refill_date").addClass("show");
                    }else 
                    */
                    if(this.medication.frequency == ""){
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
            readTour: function(){
                axios.post('{{ route("readTour") }}', {section_name:'medlist_create'});
            },
            ChangeRefill(){
                if($("#refill_date").val() == ''){
                    create_medication.medication.refill_date = '';
                }
            }
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
