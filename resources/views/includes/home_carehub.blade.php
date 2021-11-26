<div class="card widget hub shadow-sm">
    <div class="card-body">

        <a :href="carehub_url" class="hub">
            <h5 class="card-title"><i class="far fa-calendar-plus fa-2x hub"></i> CarePoints</h5>
            <div class="card-text events-today">
                <div class="card__counts">
                    <span>@{{active_discussion.length}}</span> Discussion(s) <br>
                    <span>@{{events_to_day.length}}</span> Task(s) for today <br>
                    <i class="gray" v-if="hour_first_event">Task Name at @{{hour_first_event}}</i>
                    <i class="gray" v-else>No events</i>
                </div>
            </div>
        </a>
        <a :href="carehub_url" class="btn btn-primary btn-sm" v-if="is_admin">View CarePoints</a>
    </div>
</div>

