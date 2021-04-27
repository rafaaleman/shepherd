<div class="modal fade" tabindex="-1" role="dialog" id="editMemberModal">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><span>@{{action}}</span> MEMBER</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body  row">
            
            <form method="post" id="editMemberForm" action="" class="col-md-12 p-4" v-on:submit.prevent="saveMemberPermissions()">

                <div class="section photo">
                    
                    <div class="member-info">
                        <strong>@{{member.name}} @{{member.lastname}}</strong> <br>
                        @{{ member.role_id }}
                    </div>
                </div>

                <div class="section p-3">
                    <table>
                        <tr>
                            <td><i class="far fa-envelope"></i> Email</td>
                            <td><a :href="'mailto: ' + member.email">@{{ member.email }}</a></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-mobile-alt"></i> Phone</td>
                            <td><a :href="'tel: ' + member.phone">@{{ member.phone }}</a></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-map-marker-alt"></i> Address</td>
                            <td>@{{ member.address }}</td>
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
                                    <input type="checkbox" class="custom-control-input" id="carehub" :checked="member.permissions.carehub" :disabled="!is_admin" v-model="member.permissions.carehub">
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
                                    <input type="checkbox" class="custom-control-input" id="lockbox" :checked="member.permissions.lockbox" :disabled="!is_admin" v-model="member.permissions.lockbox">
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
                                    <input type="checkbox" class="custom-control-input" id="medlist" :checked="member.permissions.medlist" :disabled="!is_admin" v-model="member.permissions.medlist">
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
                                    <input type="checkbox" class="custom-control-input" id="resources" :checked="member.permissions.resources" :disabled="!is_admin" v-model="member.permissions.resources">
                                    <label class="custom-control-label" for="resources"></label>
                                </div>
                            </td>
                        </tr>
                        

                    </table>
                </div>


                <input type="hidden" name="id" id="id" required v-model="member.id">
                <button class="btn btn-primary loadingBtn btn-lg mt-2" type="submit" data-loading-text="Saving..." id="savePermissionsBtn">Save</button>
            </form>
        </div>
        </div>
    </div>
</div>

@push('css')
@endpush



@push('scripts')
<script>

</script>
@endpush
