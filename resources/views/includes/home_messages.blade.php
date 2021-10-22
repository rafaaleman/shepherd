

<div class="card widget message shadow-sm">
    <div class="card-body">

        <a :href="messages_url" class="hub">
            <h5 class="card-title"><i class="fas fa-comments fa-2x"></i> Messages</h5>
            <div class="card-text">
                <span>4</span> Unread Message(s) <br>
                <i class="gray">Last message from TeamMemberName</i>
            </div>
        </a>
        <a class="btn btn-primary btn-sm mt-2" v-if="is_admin">Send Message</a>
    </div>
</div>

