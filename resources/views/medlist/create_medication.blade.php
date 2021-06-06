@extends('layouts.app')

@section('content')
<div class="container" id="create_medication">

    <form method="POST" action="#" style="width: 100%;" class="row mx-0 fs-0 justify-content-center" v-on:submit.prevent="createMedication()">
        @csrf

        <div class="col-md-12">
            <h3>Add medication</h3>
        </div>

        <div class="w-100">


            <div class="card  my-2 col-12 shadow-sm">
                <div class="card-body row">
                    <div class=" col-12 col-sm-12 col-lg-6 my-2 ">
                        <label for="medicine" class="label">Medicine</label>
                        <input id="medicine" type="text" class="form-control only-border-bottom " name="medicine" value="" placeholder="Medicine" required autocomplete="name" S v-model="medication.medicine">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 my-2 accordion" id="dosageAcordion">
                    <div class="card my-2 shadow-sm">
                        <div class="card-body" id="headingDosage">
                            <h2 class="mb-0 toggle-data">
                                <button class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapseDosage" aria-expanded="true" aria-controls="collapseDosage">
                                    Dosage @{{ medication.dosage }}
                                </button>
                            </h2>
                        </div>

                        <div id="collapseDosage" class="collapse"  aria-labelledby="headingDosage" data-parent="#dosageAcordion">
                            <div class="card-body">
                                <div class="form-group row mx-1" id="careteam">
                                    <div class="col-md-12 members px-0" id="" class="collapse" aria-labelledby="headingDosage" data-parent="#careteam">
                                        <template>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>160 mg</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                            <input type="radio" value="160 mg" v-model="medication.dosage">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>80 mg/0.8 mL</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                            <input type="radio" value="80 mg/0.8 mL" v-model="medication.dosage">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>160 mg/5 mL</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="160 mg/5 mL" v-model="medication.dosage">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>650 mg/25 mL</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                            <input type="radio" value="650 mg/25 mL" v-model="medication.dosage">
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
                                <button class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapsefrequency" aria-expanded="true" aria-controls="collapsefrequency">
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
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>6 h</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="6" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>8 h</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="8" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>12 h</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="12" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>24 h</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="24" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>48 h</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="48" v-model="medication.frequency">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                        </center>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="member w-100 ml-0 mb-2">
                                                <div class="row p-0">
                                                    <div class="data col-11 p-0 pl-4" style="top:5px"><label>72 h</label></div>
                                                    <div class="col-1 justify-content-center align-items-center ccheck">
                                                        <center>
                                                        <label class="customcheck">
                                                        <input type="radio" value="72" v-model="medication.frequency">
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
                        <input id="time" type="time" class="form-control no-border" name="time" value="" required autocomplete="time" onclick="" v-model="medication.time" placeholder="Time" min="{{ $date_now->format('g:i a') }}">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 my-2 accordion" id="daysAcordion">
                    <div class="card my-2 shadow-sm">
                        <div class="card-body" id="headingDays">
                            <h2 class="mb-0 toggle-data">
                                <button class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapseDays" aria-expanded="true" aria-controls="collapseDays">
                                    Days @{{ medication.days }}
                                </button>
                            </h2>
                        </div>

                        <div id="collapseDays" class="collapse" aria-labelledby="headingDays" data-parent="#daysAcordion">
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
                                                        <input type="radio" value="1" v-model="medication.days">
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
                                                        <inp<input type="radio" value="{{$i}}" v-model="medication.days">
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
                                                        <input type="radio" value="15" v-model="medication.days">
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
                                                        <input type="radio" value="30" v-model="medication.days">
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

<style>
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
}

/* On mouse-over, add a grey background color */
.customcheck:hover input ~ .checkmark {
    background-color: #369bb6;
}

/* When the checkbox is checked, add a blue background */
.customcheck input:checked ~ .checkmark {
    background-color: #369bb6;
    border-radius: 25px;
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

</style>
@endpush

@push('scripts')
<script>
    const create_event = new Vue({
        el: '#create_medication',
        created: function() {
            console.log('create_medication');
        },
        data: {
            current_slug: '{{$loveone->slug}}',
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
               // creator_id: "{{ $medication->creator_id ?? '' }}",
            }
        },
        filters: {},
        computed: {},
        methods: {
            createMedication: function() {
                //console.log(this.current_slug);return false;
                

                //console.log(this.current_slug);
                $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                //this.event.phone = this.event.phone.replace(/\D/g,'');
                
                
                   // console.log(url_succes);
                //if(this.event.assigned.length > 0){
                    var url = '{{ route("medlist.create") }}';
                    
                    axios.post(url, this.medication).then(response => {
    
                        if (response.data.success) {
                            msg = 'Your medication was saved successfully!';
                            icon = 'success';
                            //this.medication.id = response.data.data.medication.id;

                            
                            swal(
                                'Your medication was saved successfully!',
                                '',
                                'success'
                            ).then((value) => {
                                    var url_medlist= '{{ route("medlist", ["*SLUG*"]) }}';
                                        url_medlist = url_medlist.replace('*SLUG*', this.current_slug);
                                    window.location =  url_medlist;
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
                /*}else{
                    msg = 'has not assigned the sale to anyone. Please try again';
                    icon = 'error';
                    $("#collapseMemers").addClass("show");
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    swal(msg, "", icon);
                }*/
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