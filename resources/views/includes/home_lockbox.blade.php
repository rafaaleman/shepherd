<div class="card widget lockbox shadow-sm">
    <div class="card-body">

        <a :href="lockbox_url" class="hub">
            <h5 class="card-title"><i class="fas fa-file-medical fa-2x"></i> LockBox</h5>
            <div class="card-text">
                <div class="card__count">
                    <span>@{{lockBox_count}}</span> Files in your lockbox <br />
                    <i class="gray">Last updated @{{lockbox_lastUpdated}}</i>
                </div>
                <a class="btn btn-primary btn-sm mt-2" v-if="is_admin" :href="lockbox_url">View Documents</a>
            </div>
        </a>
        
    </div>
</div>

