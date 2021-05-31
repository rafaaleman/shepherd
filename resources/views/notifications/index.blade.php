@extends('layouts.app')

@section('content')
<div class="container"  id="notifications">
    <div class="row">
        <div class="col-md-12">
            <h4>Notifications</h4>

            @foreach ($user_notifications as $notification)
                <div class="card shadow-sm d-flex justify-content-between notification p-4">
                    <i class="far fa-calendar-plus fa-2x mr-3 mt-2"></i>
                    <div class="info">
                        <div class="title mb-0">{{ $notification->name }}</div>
                        <div class="event_date mb-3 text-black-50">{{ $notification->event_date}}</div>
                        <div class="event-desc text-black-50">
                            {{$notification->description}}
                        </div>
                    </div>
                    @if ($notification->read == 0)
                        <i class="fas fa-bell text-danger ml-3 mt-2"></i>
                    @else
                        <i class="far fa-bell text-black-50 ml-3 mt-2"></i>
                    @endif
                </div>
            @endforeach
            
        </div>
    </div>
    
</div>
@endsection

@push('styles')
<style>

.notification{
    flex-direction: row
}

.notification .info{
    width: 95%;
}

.notification .title{
        font-weight: bold;
        font-size: 20px;
    }
}
</style>
@endpush

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
        
        }
    });
</script>
@endpush