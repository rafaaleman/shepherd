<div class="card widget lockbox shadow-sm">
    <div class="card-body">

        <a :href="lockbox_url" class="hub">
            <h5 class="card-title"><i class="fas fa-file-medical fa-2x"></i> LockBox</h5>
            <div class="card-text">
                <span>@{{lockBox_count}}</span> Files in your lockbox <br />
                <i class="gray">Last updated yesterday</i>
            </div>
        </a>
        <a class="btn btn-primary btn-sm" v-if="is_admin">Upload Document</a>
    </div>
</div>

