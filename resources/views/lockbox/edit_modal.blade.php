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

            <div class="row d-flex justify-content-between mt-4 mx-1 mx-sm-3 mb-0 pb-0 border-0">
                <div class="tabs active" id="tab03">
                    <h6 class="font-weight-bold">Document</h6>
                </div>
                <div class="tabs" :class="(auth_user == fillDocument.user_id || auth_role == 1) ? 'show' :'d.none'" id="tab04">
                    <h6 class="text-muted">Permission</h6>
                </div>
            </div>
            <div class="line"></div>
            <form method="post" id="editDocumentform" class="col-md-12" v-on:submit.prevent="editDocument()">  

            <fieldset id="tab031" class="show col-md-12 p-3" :class="(docPermissions.u == 1 || auth_role == 1) ? 'show' :'d.none'">
                          
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

                
        </fieldset>

            <fieldset id="tab041" class="col-md-12 p-3">
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
                                    <input type="checkbox" class="custom-control-input" :disabled="c.role == 'admin'" :checked="c.permissions.r == 1" :id=`read-${c.id}` @change="assignPermission('r',c.id)">
                                    <label class="custom-control-label" :for=`read-${c.id}` ></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" :disabled="c.role == 'admin'" :checked="c.permissions.u == 1" :id=`update-${c.id}` @change="assignPermission('u',c.id)">
                                    <label class="custom-control-label" :for=`update-${c.id}`></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" :disabled="c.role == 'admin'" :checked="c.permissions.d == 1" :id=`delete-${c.id}` @change="assignPermission('d',c.id)">
                                    <label class="custom-control-label" :for=`delete-${c.id}`></label>
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
