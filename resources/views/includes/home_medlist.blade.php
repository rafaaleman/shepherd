<div class="card widget medlist shadow-sm">
    <div class="card-body">

        <a :href="medlist_url" class="medlist">
            <h5 class="card-title"><i class="fas fa-prescription-bottle-alt fa-2x"></i> Medlist</h5>
            <p class="card-text medlist-today ">
                <span>@{{count_medications}}</span> Medications for today <br>
            </p>
            <div class="loading-medlist">
                <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading medications...
            </div>
        </a>
        <a :href="medlist_add_url" class="btn btn-primary btn-sm " v-if="is_admin">Add new medication</a>
    </div>
</div>



