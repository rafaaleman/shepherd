<div class="modal fade" tabindex="-1" role="dialog" id="inviteMemberModal">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><span>INVITE A</span> MEMBER</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body row">
            
            <form method="post" id="searchMemberForm" class="col-md-12 p-3" v-on:submit.prevent="searchMember()">

                
                <label for="s_email" class="text-black-50">Search by email:</label>
                <div class="d-flex mb-4">
                    <input id="s_email" type="email" class="form-control mr-2" name="s_email" autofocus required>
                    <button class="btn btn-primary searchBtn"> <i class="fas fa-search"></i> </button>
                </div>
            </form>

            

            <form method="post" id="inlcudeMemberForm" class="col-md-12 p-3 d-none" v-on:submit.prevent="addMember2Careteam()">

                <div class="section ">
                    <table>
                        <tr>
                            <td>Role:</td>
                            <td>
                                <select name="role" id="role" v-model="member.role_id" class="form-control" :disabled="(member.role_id == 'admin')" required>
                                    @foreach ($roles as $key => $role)
                                        <option value="{{$key}}" {{ ($key == 'admin') ? 'disabled' : ''}}>{{$role}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="">Relationship:</label></td>
                            <td>
                                <select name="relationship" id="relationship" v-model="member.relationship_id" class="form-control" required>
                                    @foreach ($relationships as $relationship)
                                        <option value="{{$relationship->id}}">{{$relationship->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="section p-3">
                    <table>
                        <tr>
                            <td class="carehub">
                                <i class="far fa-calendar-plus"></i> 
                                <span>CareHub</span>
                            </td>
                            <td align="right">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="carehub" v-model="member.permissions.carehub">
                                    <label class="custom-control-label" for="carehub"></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="lockbox">
                                <i class="fas fa-file-medical"></i> 
                                <span>LockBox</span>
                            </td>
                            <td align="right">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="lockbox" v-model="member.permissions.lockbox">
                                    <label class="custom-control-label" for="lockbox"></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="medlist">
                                <i class="fas fa-prescription-bottle-alt"></i> 
                                <span>MedList</span>
                            </td>
                            <td align="right">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="medlist" v-model="member.permissions.medlist">
                                    <label class="custom-control-label" for="medlist"></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="resources">
                                <i class="fas fa-globe"></i> 
                                <span>Resources</span>
                            </td>
                            <td align="right">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="resources" v-model="member.permissions.resources">
                                    <label class="custom-control-label" for="resources"></label>
                                </div>
                            </td>
                        </tr>
                        

                    </table>
                </div>
                <input type="hidden" name="id" id="id" required v-model="member.id">
                <button class="btn btn-primary loadingBtn btn-lg my-2" type="submit" data-loading-text="Sending..." id="includeMember">Send invite</button>
            </form>




            <form method="post" id="inviteMemberForm" class="col-md-12 px-3 d-none" v-on:submit.prevent="inviteMember()">
                <div class="col text-center">
                    <p style="font-size: 20px;" class="text-danger">No Shepherd account associated with this email.</p>
                    <a class="btn btn-primary centered" href="#!" class="text-black-50" id="sendLink" v-on:click.prevent="sendInvitation()">Send an invite</a>
                </div>            
            </form>
        </div>
        </div>
    </div>
</div>

@push('css')
@endpush



@push('scripts')
<script>
$(function(){
    $('#inviteMemberModal').on('shown.bs.modal', function (e) {

        $('#inviteMemberForm, #inlcudeMemberForm').addClass('d-none');
        $('.searchBtn').html('<i class="fas fa-search"></i>').attr('disabled', false).removeClass('disabled').removeClass('btn-success').addClass('btn-primary');
        $('#s_email').val('').attr('disabled', false);
    });
});
</script>
@endpush
