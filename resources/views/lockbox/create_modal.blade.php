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
            <div class="col-12 text-center">
                <img v-if="img_url" :src="img_url " id="upload_file" class="w-50">
            </div>
            <div class="row d-flex justify-content-between mt-4 mx-1 mx-sm-3 mb-0 pb-0 border-0">
                <div class="tabs active" id="tab01">
                    <h6 class="font-weight-bold">Document</h6>
                </div>
                <div class="tabs" id="tab02">
                    <h6 class="text-muted">Permission</h6>
                </div>
            </div>
            <div class="line"></div>
            <form method="post" id="createDocumentform" class="col-md-12 p-3" v-on:submit.prevent="createDocument()">                
                <input type="hidden" name="type" id="type" v-model="document.lockbox_types_id">
                <input type="hidden" name="status" id="status" v-model="document.status">
            <fieldset id="tab011" class="show col-md-12 p-3">
            

                <label for="name" class="text-black-50">Name:</label>
                <div class="mb-4">
                    <input id="name" type="text" class="form-control mr-2"  name="name" autofocus required v-model="document.name" :readonly ="edit_doc">
                    
                </div>
                <label for="s_email" class="text-black-50">Description:</label>
                <div class="mb-4">
                    <textarea  id="description" class="form-control mr-2" name="description" v-model="document.description" ></textarea>          
                </div>
                <div class="mb-4  text-center">        
                    <span id="ffile"></span>            
                    <label class="btn btn-primary btn-block">
                        <input id="document" name="document" type="file" class="" @change="getDoc" hidden accept=".jpg, .jpeg, .png, .pdf, .doc, .docx">
                        Choose File
                    </label>
                    
                </div>
                
            </fieldset>
            <fieldset id="tab021" class="col-md-12 p-3">
                <div class="section p-3">
                    <table class="w-100">
                        <tr>
                            <th></th>
                            <th>Read</th>
                            <th>Update</th>
                            <th>Delete</th>
                    </tr>
                        <tr v-for="c in careteam">
                            <td >
                                <span>@{{ c.name }}</span>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" :disabled="c.id == auth_user || c.role == 'admin'" :checked="c.id == auth_user || c.role == 'admin'" :id=`read-${c.id}` @change="assignPermission('r',c.id)">
                                    <label class="custom-control-label" :for=`read-${c.id}` ></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" :disabled="c.id == auth_user || c.role == 'admin'" :checked="c.id == auth_user || c.role == 'admin'" :id=`update-${c.id}` @change="assignPermission('u',c.id)">
                                    <label class="custom-control-label" :for=`update-${c.id}`></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" :disabled="c.id == auth_user || c.role == 'admin'" :checked="c.id == auth_user || c.role == 'admin'" :id=`delete-${c.id}` @change="assignPermission('d',c.id)">
                                    <label class="custom-control-label" :for=`delete-${c.id}`></label>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

            </fieldset>
            <div class="mb-4  text-center">
                <button type="submit" class="btn btn-primary btn-submit btn-block" :class="save ? 'd-inline' : 'd-none' ">Save</button>
            </div>
            <div class="mb-4  text-center">
                <button type="button"  @click="hideModal('createModal')" class="btn btn-light  btn-block">Cancel</button>
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
