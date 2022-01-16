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
            <div class="col-12 text-center mb-2">
                <a :href="document.file|urlFile" target="_blank">
                    <img :src="file|isImage" class="w-50">
                </a>
            </div>
            <div class="line"></div>

            <div class="card col-12 align-middle d-flex" >
                <div class="card-body">
                    <h5 class="card-title t1">@{{ document.name }}</h5>
                    <span class="card-text t2">@{{ document.description}}</span>
                </div>
                <button type="button" @click="hideModal('viewModal')" class="btn btn-block btn-outline-primary ">Close</button>
            </div>              
        </div>
        </div>
    </div>
</div>

