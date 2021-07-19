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
           <h3><b>@{{products.name}}</b><br> <small>@{{products.drugbank_pcid}}</small></h3>

                <a href="#" class="btn btn-link" >Side Effects</a><br>
                <a href="#" class="btn btn-link">Pregnancy / Breastfeeding</a><br>
                <a href="#" class="btn btn-link">Drug Interactions</a><br>
                <a href="#" class="btn btn-link">Dosage</a><br>
                <a href="#" class="btn btn-link">A disabled link item</a>
            <!--div class="list-group">
                <template v-for="med in medication_complete">
                    <div class="d-flex w-100 justify-content-between active">
                        <h5 class="mb-1"><b>@{{med.date_usa}}</b> @{{med.medicine}}</h5>
                        <small><b>@{{med.time}}</b></small>
                    </div>
                </template>
            </div-->
           <h4>@{{products.name}} Prescribing Information</h4>
           <p class="text-justify">is an ultra short-acting benzodiazepine used in the induction and maintenance of sedation during short (<30 minute) procedures.6 Recent trends in anesthesia-related drug development have touted the benefits of so-called "soft drugs" - these agents, such as remifentanil, are designed to be metabolically fragile and thus susceptible to rapid biotransformation and elimination as inactive metabolites.4 These "soft drugs" are useful in the context of surgical procedures, wherein a rapid onset/offset is desirable, enabling anesthesiologists to manipulate drug concentrations as needed.3,4 Remimazolam was the first "soft" benzodiazepine analog to be developed5 and was approved for use by the FDA in July 2020 under the brand name Byfavo.</p>
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
