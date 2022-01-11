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
                <a :href="document.id|urlFile" target="_blank">
                    <img :src="document.file|isImage" class="w-50">
                </a>
            </div>

            <div class="row d-flex justify-content-between mt-4 mx-1 mx-sm-3 mb-0 pb-0 border-0">
                <div class="tabs active" id="tab03">
                    <h6 class="font-weight-bold">Document</h6>
                </div>
                
                <div class="tabs" id="tab04">
                    <h6 class="text-muted">Permission</h6>
                </div>
            </div>
            <div class="line"></div>
            <form method="post" id="editDocumentform" class="col-md-12" v-on:submit.prevent="editDocument()">  

            <fieldset id="tab031" class="show col-md-12 p-3" >
                          
                <input type="hidden" name="id" id="ide" v-model="document.id">
                <input type="hidden" name="type" id="typee" v-model="document.locbox_types_id">
                <input type="hidden" name="status" id="statuse" v-model="document.status">

                <label for="name" class="text-black-50">Name:</label>
                <div class="d-flex mb-4">
                    <input id="namee" type="text" class="form-control mr-2" name="name" autofocus required v-model="document.name" :readonly ="edit_doc">                    
                </div>
                <label for="s_email" class="text-black-50">Description:</label>
                <div class="d-flex mb-4">
                    <textarea  id="descriptione" class="form-control mr-2" name="description" v-model="document.description" ></textarea>          
                </div>

                <div class="d-flex mb-4  text-center">  
                    <span id="ffile"></span>                        
                    <label class="btn btn-block btn-primary btn-submit">
                        <input id="documente" name="document" type="file" class="" @change="getDoc" hidden>
                        <u>Select Your File</u>
                    </label>                    
                </div>

                
        </fieldset>

            <fieldset id="tab041" class="col-md-12 p-3">
                <div class="section p-3">
                    <table class="w-100">
                        <tr>
                            <th></th>
                            <th>Read</th>
                    </tr>
                        <tr v-for="c in careteam">
                            <td >
                                <span>@{{ c.name }}</span>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" :disabled=" c.id == auth_user || c.role == 'admin'" :checked="c.permissions.r == 1 || c.role == 'admin'" :id=`reade-${c.id}` @change="assignPermission(c.id)">
                                    <label class="custom-control-label" :for=`reade-${c.id}` ></label>
                                </div>
                            </td>                           
                        </tr>
                    </table>
                </div>

            </fieldset>
            <div class="d-flex mb-4  text-center">
                <input type="submit" class="btn btn-block btn-primary btn-submit" value="save">
            </div>
            <div class="d-flex mb-4  text-center">
                <button type="button" @click="hideModal('editModal')" class="btn btn-block btn-outline-primary">Cancel</button>
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
