<div class="card widget team shadow-sm">
    <div class="card-body">
        
        <a :href="careteam_url" class="">
        <h5 class="card-title"><i class="fas fa-users fa-2x"></i> CareTeam</h5>
        </a>
        <div class="card-text">
            <span>@{{current_members.length}}</span> Member(s) <br>    

            <div class="pl-3 avatar-imgs">
                <div class="loading">
                    <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading members...
                </div>
                <template v-for="member in current_members">
                    <img :src="member.photo" class="member-img" :title="member.name + ' ' + member.lastname" data-bs-toggle="tooltip" data-bs-placement="bottom">
                </template>
            </div>

            <a :href="careteam_url" class="btn btn-primary btn-sm" v-if="is_admin">View Members</a>
        </div>
    </div>
</div>