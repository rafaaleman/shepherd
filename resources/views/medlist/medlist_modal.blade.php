<div class="modal fade" tabindex="-1" role="dialog" id="medlist-modal">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><span>MEDLIST</span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body ">
            
            <div class="list-group">
                <template v-for="med in medication_complete">
                    <div class="d-flex w-100 justify-content-between active">
                        <h5 class="mb-1"><b>@{{med.date_usa}}</b> @{{med.medicine}}</h5>
                        <small><b>@{{med.time}}</b></small>
                    </div>
                </template>
            </div>
           
        </div>
        </div>
    </div>
</div>

@push('css')
@endpush



@push('scripts')
<script>
$(function(){
    
});
</script>
@endpush
