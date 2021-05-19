<a :href="carehub_url" class="widget hub  shadow-sm">
    <div class="card h-100">
        <div class="card-body">
            
            <h5 class="card-title"><i class="far fa-calendar-plus fa-2x"></i> CareHub</h5>
            <p class="card-text">
                <span>@{{events_to_day.length}}</span> Event(s) for today <br>    
                <i class="gray">@{{hour_first_event}}</i>
                <div class="pl-3 avatar-imgs">
                    <div class="loading-carehub">
                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading events...
                    </div>
                    
                    <template>
                    </template>
                </div>
            </p>
        </div>
    </div>
</a>