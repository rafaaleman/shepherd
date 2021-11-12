<div class="card widget hub shadow-sm">
    <div class="card-body">

        <a :href="carehub_url" class="hub">
            <h5 class="card-title"><i class="far fa-calendar-plus fa-2x hub"></i> CarePoints</h5>
            <div class="card-text events-today">
                <span>@{{events_to_day.length}}</span> Event(s) for today <br>
                <i class="gray" v-if="hour_first_event">Event Name at @{{hour_first_event}}</i>
                <i class="gray" v-else>No events</i>
            {{--    <div class="loading-carehub">
                    <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading events...
                </div> --}}

            </div>
        </a>
        <a :href="carehub_url" class="btn btn-primary btn-sm" v-if="is_admin">View CarePoints</a>
    </div>
</div>

