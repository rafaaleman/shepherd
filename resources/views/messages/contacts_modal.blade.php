<div class="modal fade" tabindex="-1" role="dialog" id="contactsModal">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span>Create A</span> Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="users-container">

                        <div class="chat_list" v-for="cc in contacts" @click="newChat(cc)">
                            <div class="chat_people">
                                <div class="chat_img"> 
                                    <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="sunil"> 
                                </div>
                                <div class="chat_ib">
                                    <h5>@{{ cc.name }} </h5>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
    </div>
</div>