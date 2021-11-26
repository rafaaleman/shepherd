<div class="card widget resources shadow-sm">
    <div class="card-body">

        <a :href="resources_url" class="hub">
            <h5 class="card-title"><i class="fas fa-globe fa-2x"></i> Resources</h5>
            <div class="card-text">
                <div class="card__count">
                    <span>@{{articles.length}}</span> new articles and topics <br>   
                    <i class="gray" v-if="resources_date != ''">Last article published @{{resources_date}}</i>
                    <i class="gray" v-else>No articles of your interest have been published</i>
                </div>
                <a class="btn btn-primary btn-sm" :href="resources_url" v-if="is_admin">View Resources</a>
            </div>
        </a>
    </div>
</div>
