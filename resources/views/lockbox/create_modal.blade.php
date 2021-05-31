<div class="modal fade" tabindex="-1" role="dialog" id="createModal">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><span>Create A</span> New Document</h5>
            <button type="button" class="close" @click="hideModal('createModal')" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body row">
            <form method="post" id="createDocumentform" class="col-md-12 p-3" v-on:submit.prevent="createDocument()">                
                <input type="hidden" name="type" id="type" v-model="newDocument.lockbox_types_id">
                <input type="hidden" name="status" id="status" v-model="newDocument.status">

                <label for="name" class="text-black-50">Name:</label>
                <div class="d-flex mb-4">
                    <input id="name" type="text" class="form-control mr-2" :readonly ="create_type" name="name" autofocus required v-model="newDocument.name">
                    
                </div>
                <label for="s_email" class="text-black-50">Description:</label>
                <div class="d-flex mb-4">
                    <textarea  id="description" class="form-control mr-2" name="description" v-model="newDocument.description" :readonly ="create_type"></textarea>          
                </div>
                <div class="d-flex mb-4  text-center">                    
                    <label class="btn btn-block btn-primary btn-submit">
                        <input id="document" name="document" type="file" class="" @change="getDoc" hidden>
                        Choose File
                    </label>
                    
                </div>
                <div class="d-flex mb-4  text-center">
                    <input type="submit" class="btn btn-block btn-primary btn-submit" value="save">
                </div>
                <div class="d-flex mb-4  text-center">
                    <button type="button"  @click="hideModal('createModal')" class="btn btn-block ">Cancel</button>
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



@push('scripts')
<script>

</script>
@endpush
