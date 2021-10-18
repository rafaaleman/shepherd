<div class="modal fade" tabindex="-1" role="dialog" id="viewModal">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><span>View A</span> Document</h5>
            <button type="button" class="close" @click="hideModal('viewModal')" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>        
        <div class="modal-body row">
            <div class="col-12 text-center">
                <a :href="document.file|urlFile" target="_blank">
                    <img :src="document.file|isImage" class="w-50">
                </a>
            </div>
            <div class="line"></div>
            <div>
                <label for="name" class="text-black-50">Name:</label>
                <div class="d-flex mb-4">
                    @{{ document.name }}                    
                </div>
            </div>
                <label for="s_email" class="text-black-50">Description:</label>
                <div class="d-flex mb-4">
                    @{{ document.description }}
                </div>
            <div class="d-flex mb-4  text-center">
                <button type="button" @click="hideModal('viewModal')" class="btn btn-block ">Close</button>
            </div>
        </div>
        </div>
    </div>
</div>

