@extends('layouts.app')

@section('content')
<div class="container"  id="lockbox">
    <div class="row">
        <div class="col-md-12">
            <h4>Notifications</h4>
            
        </div>
    </div>
    
</div>
@endsection

@push('styles')
<style>
    
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