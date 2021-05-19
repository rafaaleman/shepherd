@extends('layouts.app')

@section('content')
<div class="container" id="create_event">

    <form method="POST" action="#" style="width: 100%;" class="row justify-content-center" v-on:submit.prevent="createEvent()">
        @csrf

        <div class="col-md-12">
            <h3>Add event</h3>
        </div>

        <div class="w-100">

            <div class="card  my-2 col-12">
                <div class="card-body row">
                    <input id="name" type="text" class="form-control col-12 col-sm-12 col-lg-6 my-2" name="name" value="" placeholder="Event Name" required autocomplete="name" autofocus v-model="event.name">
                    <input id="location" type="text" class="form-control col-12 col-sm-12 col-lg-6 my-2" name="location" value="" placeholder="Location" required autocomplete="location" autofocus v-model="event.location">
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-lg-4 my-2 accordion" id="accordionMembers">
                    <div class="card my-2">
                        <div class="card-body" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseMemers" aria-expanded="true" aria-controls="collapseMemers">
                                    Assign to...
                                </button>
                            </h2>
                        </div>

                        <div id="collapseMemers" class="collapse" aria-labelledby="headingOne" data-parent="#accordionMembers">
                            <div class="card-body">
                                <div class="form-group row" id="careteam">
                                    <div class="col-md-12 members" id="" class="collapse " aria-labelledby="headingOne" data-parent="#careteam">
                                        @foreach($careteam as $team)
                                        <template>
                                            @if($team->user != null)
                                            <div class="member w-100">
                                                <img src="{{ (!empty($team->user->photo) && $team->user->photo != null ) ? env('APP_URL').'/public'.$team->user->photo : asset('public/img/no-avatar.png')}}" class="float-left mr-3">
                                                <div class="data float-left">
                                                    <div class="name">{{ $team->user->name }} {{ $team->user->lastname }}</div>
                                                    <div class="role">{{ ucfirst($team->role) }}</div>
                                                </div>

                                                <div class=" float-right ">
                                                    <input type="checkbox" class=""  value="{{$team->id}}"  v-model="event.assigned">
                                                </div>
                                            </div>
                                            @endif
                                        </template>
                                        @endforeach
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-12 col-lg-4 my-2">
                    <div class="card  my-2 col-12">
                        <div class="card-body">
                            <input id="date" type="date" class="form-control" name="date" value="" required autocomplete="date" autofocus v-model="event.date" placeholder="Date">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-lg-4 my-2">
                    <div class="card  my-2">
                        <div class="card-body">
                            <input id="time" type="time" class="form-control" name="time" value="" required autocomplete="time" autofocus v-model="event.time" placeholder="Time">

                        </div>
                    </div>
                </div>
            </div>

            <div class="card  my-2 col-12">
                <div class="card-body row">
                    <textarea id="notes" rows="7" type="date" class="form-control" name="notes" required autocomplete="notes" v-model="event.notes" placeholder="Notes"></textarea>
                </div>
            </div>



            <div class="form-group row mb-0">
                <div class="col-md-12 mt-4 justify-content-center">
                    <center>
                        <button class="btn btn-primary loadingBtn btn-lg" type="submit" data-loading-text="Loading..." id="saveBtn">
                            Save
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
<style>

</style>
@endpush

@push('scripts')
<script>
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
                name: "{{ $event->name ?? '' }}",
                location: "{{ $event->location ?? '' }}",
                date: "{{ $event->date ?? '' }}",
                time: "{{ $event->time ?? '' }}",
                notes: "{{ $event->address ?? '' }}",
                assigned:  [],
                creator_id: "{{ $event->creator_id ?? '' }}",
                status: 1,
            }
        },
        filters: {},
        computed: {},
        methods: {
            createEvent: function() {
                //console.log(this.current_slug);
                $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                //this.event.phone = this.event.phone.replace(/\D/g,'');
                var url_succes = '{{ route("carehub", "*SLUG*") }}';
                url_succes = url_succes.replace('*SLUG*', this.current_slug);
                   // console.log(url_succes);
                if(this.event.assigned.length > 0){
                    var url = '{{ route("carehub.event.create") }}';
                    
                    axios.post(url, this.event).then(response => {
                        console.log(response.data);

                        if (response.data.success) {
                            msg = 'Your event was saved successfully!';
                            icon = 'success';
                            this.event.id = response.data.data.event.id;
                            swal(msg, "", icon)
                            .then((value) => {
                                window.location = url_succes;
                            });
                            
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
                    msg = 'has not assigned the sale to anyone. Please try again';
                    icon = 'error';
                    $("#collapseMemers").addClass("show");
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    swal(msg, "", icon);
                }
            }
        }
    });
</script>
@endpush