
<a :href="medlist_url" class="widget medlist shadow-sm">
    <div class="card h-100">
        <div class="card-body">
            
            <h5 class="card-title"><i class="far fa-calendar-plus fa-2x"></i> Medlist</h5>
            <p class="card-text medlist-today">
                <span>@{{count_medications}}</span> Medications for today <br>    
            </p>
            <div class="loading-medlist">
                    <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading medications...
            </div>
        </div>
    </div>
</a>