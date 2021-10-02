@extends('layouts.app')

@section('content')
<div class="container"  id="notifications">
    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-4">Notifications</h4>

            @if (count($user_notifications) > 0)
                
            
                @foreach ($user_notifications as $notification)
                    <div class="card shadow-sm d-flex justify-content-between notification p-4 mb-3 {{ ($notification->read == 1) ? 'read' : '' }}" id="n{{ $notification->nid }}">
                        <i class="{{ $notification->icon }} fa-2x mr-3 mt-2" ></i>
                        <div class="info">
                            <div class="title mb-0 text-primary">{{ $notification->name }}</div>
                            <div class="event_date mb-3 text-black-50">{{ $notification->event_date}}</div>
                            <div class="event-desc text-black-50">
                                {{$notification->description}}
                            </div>
                        </div>
                        @if ($notification->read == 0)
                            <a href="#" class="readNotification" @click="readNotification({{$notification->nid}})" >
                                <small class="text-danger text-nowrap">Mark as read <i class="fas fa-bell mt-2"></i></small>
                            </a>
                        @else
                            <i class="far fa-bell text-black-50 ml-3 mt-2"></i>
                        @endif
                    </div>
                @endforeach
            @else
                <h5 class="mt-5"> No new notifications</h5>
            @endif    
        </div>
    </div>
    
</div>
@endsection


@push('scripts')
<script>
   const notifications = new Vue ({
        el: '#notifications',
        created: function() {
            console.log('notifications');
        },
        data: {
            notifications: [],
            
        },
        filters: {
            mayuscula: function (value) {
                if (!value) return ''
                value = value.toString();
                return value.toUpperCase(); 
            },
        },
        computed:{ 
        },
        methods: {
        
            readNotification: function(id){

                $('#n' + id + ' .readNotification').hide();
                var url = '{{ route("notifications.readNotification") }}';
                data = {
                    id: id
                };
                
                axios.post(url, data)
                .then(response => {
                    // console.log(response.data);
                    
                    if(response.data.success){
                        $('#n' + id + ' .readNotification').html('<i class="far fa-bell text-black-50 ml-3 mt-2"></i>').show();
                        $('#n' + id).addClass('read');
                        
                    } else {
                        $('#n' + id + ' .readNotification').show();
                    }

                    
                }).catch( error => {
                    console.log(error);
                    msg = 'There was an error. Please try again. Error: ' + error;
                    swal('Error', msg, 'error');
                    $('#n' + id + ' .readNotification').show();
                });
            }
        }
    });
</script>
@endpush