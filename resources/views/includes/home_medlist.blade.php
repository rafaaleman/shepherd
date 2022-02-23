<div class="card widget medlist shadow-sm">
    <div class="card-body">

        <a :href="medlist_url" class="medlist">
            <h5 class="card-title"><i class="fas fa-prescription-bottle-alt fa-2x"></i> Medlist</h5>
            <p class="card-text medlist-today ">
                <div class="card__count">
                    <span>@{{count_medications}}</span> Medications for today <br>
                    <i class="gray" v-if="medlist_date != ''">@{{medlist_date}}</i>
                    <i class="gray" v-else>No dosage</i>
                </div>
            </p>
            <a :href="medlist_add_url" class="btn btn-primary btn-sm text-white" v-if="is_admin">View Medications</a>
        </a>
    </div>
</div>



