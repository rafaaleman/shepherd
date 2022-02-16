@extends('layouts.app')

@section('content')
<div class="container" id="create_event">

    <form method="POST" action="#" style="width: 100%;" class="row mx-0 fs-0 justify-content-center" v-on:submit.prevent="createEvent()">
        @csrf

        <div class="col-md-12">
            <h3>Assign A Task</h3>
        </div>

        <div class="w-100">

            <div class="card  my-2 col-12 shadow-sm">
                <div class="card-body row">
                    <div class=" col-12 col-sm-12 col-lg-6 my-2 ">
                        <label for="name" class="label">Task Name</label>
                        <input data-title="Welcome to CarePoints" data-intro="You can start by typing the name of your task here" id="name" type="text" class="form-control only-border-bottom " name="name" value="" placeholder='e.g. "Take Mom to the Dr"' required autocomplete="name" S v-model="event.name">

                    </div>
                    <div class="col-12 col-sm-12 col-lg-6 my-2">
                        <label for="location" class="label">Address</label>
                        <input data-title="Welcome to CarePoints" data-intro="Then you can add the location of the task or event here" id="location" type="text" class="form-control only-border-bottom" name="location" value="" placeholder='e.g. "123 main street" ' required autocomplete="location" S v-model="event.location">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6 col-xl-5  my-2 accordion" id="accordionMembers">
                    <div class="card my-2 shadow-sm">
                        <div class="card-body" id="headingOne">
                            <h2 class="mb-0" id="toggle-members">
                                <button data-title="Welcome to CarePoints" data-intro="Here you can select who to assign this task to" class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapseMemers" aria-expanded="true" aria-controls="collapseMemers">
                                    Assign to...
                                </button>
                            </h2>
                        </div>

                        <div id="collapseMemers" class="collapse" aria-labelledby="headingOne" data-parent="#accordionMembers">
                            <div class="card-body  py-0">
                                <div class="form-group row mx-1" id="careteam">
                                    
                                <div class="custom-control custom-checkbox mr-sm-2 float-right pb-2">
                                    <input type="checkbox" class="custom-control-input" id="customControlAutosizing" v-model="selectallval" value="1" v-on:click="selectAll()">
                                    <label class="custom-control-label" for="customControlAutosizing">Select all</label>
                                </div>
                                
                                    <div class="col-md-12 members px-0" id="" class="collapse" aria-labelledby="headingOne" data-parent="#careteam">
                                        @php
                                            $select_all = array();
                                        @endphp
                                        @foreach($careteam as $team)

                                        <template>
                                            @if($team->user != null)
                                            <div class="member w-100 ml-0 mb-2 row">
                                                <img src="{{ (!empty($team->user->photo) && $team->user->photo != null ) ? $team->user->photo : '/img/no-avatar.png'}}" class="">
                                                
                                                <div class="data text-truncate col-6">
                                                    <div class="name text-truncate">{{ $team->user->name }} {{ $team->user->lastname }} </div>
                                                    <div class="role">{{ ucfirst($team->role) }}</div>
                                                </div>

                                                <div class="ccheck col-4 col-lg-3 ">
                                                    <center>
                                                    <label class="customcheck">
                                                        <input type="checkbox" value="{{$team->user_id}}"  v-model="event.assigned">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    
                                                        <!--input type="checkbox" class="form-check"  value="{{$team->id}}"  v-model="event.assigned"-->
                                                    </center>
                                                </div>
                                            </div>
                                            @php
                                                array_push($select_all, $team->user_id);
                                            @endphp
                                            @endif
                                        </template>
                                        @endforeach
                                        
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-lg-6 col-xl-4 my-2">
                    <div class="card shadow-sm my-2 col-12">
                        <div class="card-body">
                            <input data-title="Welcome to CarePoints" data-intro="Here you can select the data of the task" id="carehub_datepicker" type="text" class="form-control no-border" name="date" required autocomplete="date"  placeholder="Select Date" >
                        </div>
                    </div>
                </div>
                <div class="col-12  col-xl-3 my-2">
                    <div class="card shadow-sm my-2">
                        <div class="card-body">
                            <input data-title="Welcome to CarePoints" data-intro="Choose the time of the event here in 15 minute increments" id="time" type="text" class="no-border" name="time" value="--:--" required  onclick="" v-model="event.time" placeholder="Select Time">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm my-2 col-12">
                <div class="card-body row">
                    <textarea data-title="Welcome to CarePoints" data-intro="And if you have any notes about this task, you can write them here. They will be visible to others." id="notes" rows="7" type="date" class="form-control no-border no-focus" name="notes" autocomplete="notes" v-model="event.notes" placeholder="Notes" maxlength="500"></textarea>
                </div>
            </div>



            <div class="form-group row mb-0">
                <div class="col-md-12 mt-4 mb-4 justify-content-center">
                    <center>
                        <button data-title="Welcome to CarePoints" data-intro="Don't forget to save your task here. This will send an email reminder to the people you assign this task to." class="btn btn-primary loadingBtn btn-lg" type="submit" data-loading-text="Saving..." id="saveBtn">
                            Add
                        </button>
                    </center>
                    <input type="hidden" name="id" v-model="event.id" value="">
                    <input type="hidden" name="loveone_id" v-model="event.loveone_id"> 
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
                    create_event.readTour()
                }
            }).start();
        @endif

        var picker = new Pikaday({
            field: document.getElementById('carehub_datepicker'),
            format: 'Y-MM-DD',
            minDate: moment().toDate(),
            onSelect: function() {
                create_event.event.date = this.getMoment().format('YYYY/MM/DD')
            }
        });

        $('#time').datetimepicker({format: 'LT',stepping: 15});
        $('#time').on('dp.change',function(e){
            create_event.event.time= moment(e.date,"h:mm:ss a").format('h:mm a');
        });
    });
    
    const create_event = new Vue({
        el: '#create_event',
        created: function() {
            console.log('create_event');
        },
        data: {
            current_slug: '{{$loveone->slug}}',
            event: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: "{{ $event->id ?? 0 }}",
                loveone_id: "{{ $loveone->id ?? 0 }}",
                loveone_name: "{{ $loveone->firstname. ' ' . $loveone->lastname ?? '' }}",
                name: "{{ $event->name ?? '' }}",
                location: "{{ $event->location ?? '' }}",
                date: "{{ $event->date ?? '' }}",
                time: "{{ $event->time ?? '' }}",
                notes: "{{ $event->address ?? '' }}",
                assigned:  [],
                creator_id: "{{ $event->creator_id ?? '' }}",
                status: 1,
            },
            select_all: @json($select_all),
            selectallval: ''
        },
        filters: {},
        computed: {},
        methods: {
            createEvent: function() {
                //console.log(this.current_slug);return false;
                create_event.event.time= moment(this.event.time,"h:mm:ss a").format('HH:mm');


                //console.log(this.current_slug);
                $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                //this.event.phone = this.event.phone.replace(/\D/g,'');
                
                
                   // console.log(url_succes);
                if(this.event.assigned.length > 0){
                    var url = '{{ route("carehub.event.create") }}';
                    
                    axios.post(url, this.event).then(response => {
    
                        if (response.data.success) {
                            msg = 'Your event was saved successfully!';
                            icon = 'success';
                            //this.event.id = response.data.data.event.id;

                            
                            

                            swal({
                                title: "",
                                text: "Your event was saved successfully!",
                                icon: "success",
                                buttons: [
                                    'Return to carehub',
                                    "Continue!"
                                ],
                            }).then((value) => {
                                //alert(value);
                                if(value){
                                    var url_carehub= '{{ route("carehub", ["*SLUG*"]) }}';
                                        url_carehub = url_carehub.replace('*SLUG*', this.current_slug);
                                    window.location =  url_carehub;
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
                    msg = 'Please assign the task to yourself or another CareTeam member.';
                    icon = 'error';
                    $("#collapseMemers").addClass("show");
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    swal(msg, "", icon);
                }
            },
            selectAll : function(){
                if(!this.selectallval == true){
                    this.event.assigned = this.select_all
                }else{
                    this.event.assigned = []
                }
            },
            readTour: function(){
                axios.post('{{ route("readTour") }}', {section_name:'carepoints_create'});
            }
        }
    });
    
    $('.collapse').on('show.bs.collapse', function () {
        // do something…
        $("#toggle-members").addClass("dropup");
    })

    $('.collapse').on('hidden.bs.collapse', function () {
        // do something…
        $("#toggle-members").removeClass("dropup");

    })
</script>
@endpush