@extends('layouts.app')

@section('content')
<div class="container"  id="create_event">

    <form method="POST" action="#" style="width: 100%;" class="row" v-on:submit.prevent="createEvent()">
        @csrf

        <div class="col-md-12">
            <h3>Add event</h3>
        </div>

        <div class="col-md-6 mt-5">

            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">Event Name </label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name" value="" required autocomplete="name" autofocus v-model="event.name">
                </div>
            </div>

            <div class="form-group row">
                <label for="location" class="col-md-4 col-form-label text-md-right">Location</label>

                <div class="col-md-6">
                    <input id="location" type="text" class="form-control" name="location" value="" required autocomplete="location" autofocus v-model="event.location">
                </div>
            </div>

            <div class="form-group row" id="careteam">
                <label for="date" class="col-md-4 col-form-label text-md-right">Assign to...</label>
                <div class="col-md-6 members">
                    @foreach($careteam as $team)
                            <div class="member" style="padding:5px;" for="member{{$team->user->id}}">
                                <div class="d-inline-block align-top" style="width:25%">
                                    <img src="{{ (!empty($team->user->photo) && $team->user->photo != null ) ? $team->user->photo : asset('public/img/no-avatar.png')}}" class="float-left">
                                </div>
                                <div class="d-inline-block align-top data" style="width:55%">
                                    <div class="name">{{ $team->user->name }} {{ $team->user->lastname }}</div>
                                    <div class="role">{{ ucfirst($team->role) }}</div>
                                </div>
                               
                                <div class="d-inline-block align-top" style="width:10%;padding-top:5px">
                                    <input type="checkbox" class="form-control" name="assigned" id="member{{$team->user->id}}" v-model="event.assigned">
                                </div>
                                
                                
                            </div>
                    @endforeach
                </div>
                
            </div>
            
            
            <div class="form-group row">
                <label for="date" class="col-md-4 col-form-label text-md-right">Date</label>

                <div class="col-md-6">
                    <input id="date" type="date" class="form-control" name="date" value="" required autocomplete="date" autofocus v-model="event.date">
                </div>
            </div>

            <div class="form-group row">
                <label for="time" class="col-md-4 col-form-label text-md-right">Time</label>

                <div class="col-md-6">
                    <input id="time" type="time" class="form-control" name="time" value="" required autocomplete="time" autofocus v-model="event.time">
                </div>
            </div>

            <div class="form-group row">
                <label for="notes" class="col-md-4 col-form-label text-md-right">Notes</label>

                <div class="col-md-6">
                    <textarea id="notes" type="date" class="form-control" name="notes" required autocomplete="notes" v-model="event.notes"></textarea>
                </div>
            </div>


            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4 mt-4">
                    <button class="btn btn-primary loadingBtn btn-lg" type="submit" data-loading-text="Loading..." id="saveBtn">
                        Save
                    </button>
                    <input type="hidden" name="id" v-model="event.id" value="">
                    <input type="hidden" name="loveone_id" v-model="event.loveone_id" >
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mt-5">

            

            
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

    const create_event = new Vue ({
        el: '#create_event',
        created: function() {
            console.log('create_event');
        },
        data: {
            event: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: "{{ $event->id ?? 0 }}",
                loveone_id: "{{ $loveone->id ?? 0 }}",
                name: "{{ $event->name ?? '' }}",
                location:"{{ $event->location ?? '' }}",
                date:"{{ $event->date ?? '' }}",
                time:"{{ $event->time ?? '' }}",
                notes:"{{ $event->address ?? '' }}",
                assigned:"",
                status:1,
            }
        },
        filters: {
        },
        computed:{ 
        },
        methods: {
            createEvent: function() {
                console.log('creating');
                $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                //this.event.phone = this.event.phone.replace(/\D/g,'');

                var url = '{{ route("carehub.event.create") }}';
                axios.post(url, this.event).then(response => {
                    console.log(response.data);
                    
                    if(response.data.success){
                        msg  = 'Your event was saved successfully!';
                        icon = 'success';
                        this.event.id = response.data.data.event.id; 
                    } else {
                        msg = 'There was an error. Please try again';
                        icon = 'error';
                    }
                    
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    swal(msg, "", icon);
                        
                }).catch( error => {
                    
                    txt = "";
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    $.each( error.response.data.errors, function( key, error ) {
                        txt += error + '\n';
                    });
                    
                    swal('There was an Error', txt, 'error');
                });
            }
        }
    });
    
</script>
@endpush
