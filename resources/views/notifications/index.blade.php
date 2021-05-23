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
                        <div class="title mb-0">Carehub Notification</div>
                        <div class="event_date mb-3 text-black-50">Today at 20:00</div>
                        <div class="event-desc text-black-50">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Veniam, alias sunt quia sapiente aspernatur hic accusamus. 
                        </div>
                    </div>
                    <i class="fas fa-bell text-danger ml-3 mt-5"></i>
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