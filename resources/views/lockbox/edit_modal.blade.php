<div class="modal fade" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><span>Edit A</span> Document</h5>
            <button type="button" class="close" @click="hideModal('editModal')" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body row">
            <div class="col-12 text-center">
                <a :href="fillDocument.file|urlFile" target="_blank">
                    <img :src="fillDocument.file|isImage" class="w-50">
                </a>
            </div>
            <form method="post" id="editDocumentform" class="col-md-12 p-3" v-on:submit.prevent="editDocument()">                
                <input type="hidden" name="id" id="id" v-model="fillDocument.id">
                <input type="hidden" name="type" id="type" v-model="fillDocument.locbox_types_id">
                <input type="hidden" name="status" id="status" v-model="fillDocument.status">

                <label for="name" class="text-black-50">Name:</label>
                <div class="d-flex mb-4">
                    <input id="name" type="text" class="form-control mr-2" name="name" autofocus required v-model="fillDocument.name" :readonly ="create_type">
                    
                </div>
                <label for="s_email" class="text-black-50">Description:</label>
                <div class="d-flex mb-4">
                    <textarea  id="description" class="form-control mr-2" name="description" v-model="fillDocument.description" :readonly ="create_type"></textarea>          
                </div>

                <div class="d-flex mb-4  text-center">  
                    <span id="ffile"></span>                        
                    <label class="btn btn-block btn-primary btn-submit">
                        <input id="document" name="document" type="file" class="" @change="getDoc" hidden>
                        Choose File
                    </label>                    
                </div>

                <div class="d-flex mb-4  text-center">
                    <input type="submit" class="btn btn-block btn-primary btn-submit" value="save">
                </div>
                <div class="d-flex mb-4  text-center">
                    <button type="button" @click="hideModal('editModal')" class="btn btn-block ">Cancel</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

@push('css')
<style>
    [hidden] {
  display: none !important;
}
</style>
@endpush
