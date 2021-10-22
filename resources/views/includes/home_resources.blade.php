<div class="card widget resources shadow-sm">
    <div class="card-body">

        <a :href="resources_url" class="hub">
            <h5 class="card-title"><i class="fas fa-globe fa-2x"></i> Resources</h5>
            <div class="card-text">
                News and trending topics <br>   
                <div class="pl-3 avatar-imgs">
                </div>
                <i class="gray">10 new articles</i>
            </div>
        </a>
        <a class="btn btn-primary btn-sm mt-2" v-if="is_admin">Read News</a>
    </div>
</div>

