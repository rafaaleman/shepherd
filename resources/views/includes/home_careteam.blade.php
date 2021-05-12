<a :href="careteam_url" class="widget team">
    <div class="card">
        <div class="card-body">
            
            <h5 class="card-title"><i class="fas fa-users fa-2x"></i> CareTeam</h5>
            <p class="card-text">
                <span>@{{current_members.length}}</span> Member(s) <br>    

                <div class="pl-3 avatar-imgs">
                    <div class="loading">
                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading members...
                    </div>
                    <template v-for="member in current_members">
                        <img :src="member.photo" class="member-img" :title="member.name + ' ' + member.lastname" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    </template>
                </div>
            </p>
        </div>
    </div>
</a>