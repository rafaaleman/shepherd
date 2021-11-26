@extends('layouts.app')

@section('content')
<div class="container" id="create_discussion">

    <form method="POST" action="#" style="width: 100%;" class="row mx-0 fs-0 justify-content-center" v-on:submit.prevent="createDiscussion()">
        @csrf

        <div class="col-md-12">
            <h3>Add discussion</h3>
        </div>

        <div class="w-100">

            <div class="card  my-2 col-12 shadow-sm">
                <div class="card-body row">
                    <div class=" col-12 col-sm-12 col-lg-6 my-2 ">
                        <label for="name" class="label">Discussion Name</label>
                        <input id="name" type="text" class="form-control only-border-bottom " name="name" value="" placeholder="Discussion Name" required autocomplete="name" v-model="discussion.name">

                    </div>
                   
                    <div class="col-12 col-sm-12 col-lg-6 my-2 accordion" id="accordionMembers">
                        <div class="card my-2 ">
                            <div class="card-body" id="headingOne">
                                <h2 class="mb-0" id="toggle-members">
                                    <button data-title="Welcome to CarePoints" data-intro="Here you can select who to assign this task to" class="btn btn-link btn-block text-left dropdown-toggle" type="button" data-toggle="collapse" data-target="#collapseMemers" aria-expanded="true" aria-controls="collapseMemers">
                                        Assign to...
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseMemers" class="collapse" aria-labelledby="headingOne" data-parent="#accordionMembers">
                                <div class="card-body p-0">
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

                                                    <div class="ccheck col-4">
                                                        <center>
                                                        <label class="customcheck">
                                                            <input type="checkbox" value="{{$team->user_id}}"  v-model="discussion.assigned">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        
                                                            <!--input type="checkbox" class="form-check"  value="{{$team->id}}"  v-model="discussion.assigned"-->
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
                </div>
            </div>

            

            <div class="card shadow-sm my-2 col-12">
                <div class="card-body row">
                    <textarea id="notes" rows="7" type="date" class="form-control no-border no-focus" name="notes" autocomplete="notes" v-model="discussion.notes" placeholder="Notes" maxlength="250"></textarea>
                </div>
            </div>



            <div class="form-group row mb-0">
                <div class="col-md-12 mt-4 mb-4 justify-content-center">
                    <center>
                        <button class="btn btn-primary loadingBtn btn-lg" type="submit" data-loading-text="Saving..." id="saveBtn">
                            Save
                        </button>
                    </center>
                    <input type="hidden" name="id" v-model="discussion.id" value="">
                    <input type="hidden" name="loveone_id" v-model="discussion.loveone_id"> 
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
    padding-left: 5px;
    padding-right: 5px;
}
.customcheck {
    display: block;
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
    color:#78849e;
}

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
    color:red;
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


@media only screen and (max-width: 370px) {
    #collapseMemers .card-body{
        padding: .35rem;
    }        

}
</style>
@endpush

@push('scripts')

<script>
    $(function () {
        //$('#carehub_datepicker').pikaday();
       // let carehub_picker = new Pikaday({ field: document.getElementById('carehub_datepicker') });

       
    });
    const create_discussion = new Vue({
        el: '#create_discussion',
        created: function() {
            console.log('create_discussion');
        },
        data: {
            current_slug: '{{$loveone->slug}}',
            discussion: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: "{{ $discussion->id ?? 0 }}",
                loveone_id: "{{ $loveone->id ?? 0 }}",
                name: "{{ $discussion->name ?? '' }}",
                notes: "{{ $discussion->address ?? '' }}",
                creator_id: "{{ $discussion->creator_id ?? '' }}",
                status: 1,
                assigned:  [],
            },
            select_all: @json($select_all),
            selectallval: ''
        },
        filters: {},
        computed: {},
        methods: {
            createDiscussion: function() {
                //console.log(this.current_slug);return false;
                


                //console.log(this.current_slug);
                $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                //this.discussion.phone = this.discussion.phone.replace(/\D/g,'');
                
                if(this.discussion.assigned.length > 0){
                   // console.log(url_succes);
                    var url = '{{ route("carehub.discussion.create") }}';
                    
                    axios.post(url, this.discussion).then(response => {
    
                        if (response.data.success) {
                            msg = 'Your discussion was saved successfully!';
                            icon = 'success';
                            //this.discussion.id = response.data.data.discussion.id;

                            
                            

                            swal({
                                title: "",
                                text: "Your discussion was saved successfully!",
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
                    this.discussion.assigned = this.select_all
                }else{
                    this.discussion.assigned = []
                }
            }
            
        }
    });
    
    
</script>
@endpush