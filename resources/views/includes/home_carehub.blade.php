<a :href="carehub_url" class="widget hub  shadow-sm">
    <div class="card h-100">
        <div class="card-body">
            
            <h5 class="card-title"><i class="far fa-calendar-plus fa-2x"></i> CarePoints</h5>
            <p class="card-text events-today">
                <span>@{{events_to_day.length}}</span> Event(s) for today <br>    
                <i class="gray">@{{hour_first_event}}</i>
            </p>
            <div class="loading-carehub">
                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading events...
            </div>
        </div>
    </div>
</a>