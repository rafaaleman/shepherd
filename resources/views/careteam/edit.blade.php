@extends('layouts.app')

@section('content')
<div class="container editMember"  id="careteam">
            
    <div class="row">
        
        <h5 class="modal-title">EDIT MEMBER</h5>

        <div class="loading w-100 mt-3">
            <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading member...
        </div>
            
        <form method="post" id="editMemberForm" action="" class="col-md-12 row d-none" v-on:submit.prevent="saveMemberPermissions()">

            <div class="col-xs-12 col-md-6 p-3">
                <div class="section photo h-200" v-bind:style="{ backgroundImage: 'url(' + member.photo + ')' }">
                    
                    <div class="member-info">
                        <strong>@{{member.name}} @{{member.lastname}}</strong> <br>
                        <span class="role">@{{ member.role }}</span>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-6 p-3">

                <div class="section shadow-sm">
                    <table>
                        <tr>
                            <td class=""><i class="far fa-envelope"></i> Email</td>
                            <td><a :href="'mailto: ' + member.email" target="_blank">@{{ member.email }}</a></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-mobile-alt"></i> Phone</td>
                            <td><a :href="'tel: ' + member.phone" target="_blank">@{{ member.phone | formatPhoneNumber }}</a></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-map-marker-alt"></i> Address</td>
                            <td>@{{ member.address }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="section p-3 shadow-sm">
                <table>
                    <tr>
                        <td class="carehub" width="70%">
                            <i class="far fa-calendar-plus"></i> 
                            <span>CarePoints</span>
                        </td>
                        <td align="right">
                            <div class="custom-control custom-switch" v-if="is_admin">
                                <input type="checkbox" class="custom-control-input" id="carehub" :checked="member.permissions.carehub" v-model="member.permissions.carehub">
                                <label class="custom-control-label" for="carehub"></label>
                            </div>
                            <div class="" v-else>
                                <div class="" v-if="member.permissions.carehub">ON</div>
                                <div class="" v-else>OFF</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="lockbox">
                            <i class="fas fa-file-medical"></i> 
                            <span>LockBox</span>
                        </td>
                        <td align="right">
                            <div class="custom-control custom-switch" v-if="is_admin">
                                <input type="checkbox" class="custom-control-input" id="lockbox" :checked="member.permissions.lockbox" :disabled="!is_admin" v-model="member.permissions.lockbox">
                                <label class="custom-control-label" for="lockbox"></label>
                            </div>
                            <div class="" v-else>
                                <div class="" v-if="member.permissions.lockbox">ON</div>
                                <div class="" v-else>OFF</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="medlist">
                            <i class="fas fa-prescription-bottle-alt"></i> 
                            <span>MedList</span>
                        </td>
                        <td align="right">
                            <div class="custom-control custom-switch" v-if="is_admin">
                                <input type="checkbox" class="custom-control-input" id="medlist" :checked="member.permissions.medlist" :disabled="!is_admin" v-model="member.permissions.medlist">
                                <label class="custom-control-label" for="medlist"></label>
                            </div>
                            <div class="" v-else>
                                <div class="" v-if="member.permissions.medlist">ON</div>
                                <div class="" v-else>OFF</div>
                            </div>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td class="resources">
                            <i class="fas fa-globe"></i> 
                            <span>Resources</span>
                        </td>
                        <td align="right">
                            <div class="custom-control custom-switch" v-if="is_admin">
                                <input type="checkbox" class="custom-control-input" id="resources" :checked="member.permissions.resources" :disabled="!is_admin" v-model="member.permissions.resources">
                                <label class="custom-control-label" for="resources"></label>
                            </div>
                            <div class="" v-else>
                                <div class="" v-if="member.permissions.resources">ON</div>
                                <div class="" v-else>OFF</div>
                            </div>
                        </td>
                    </tr> --}}
                    

                </table>
            </div>


            <div class="section p-3 shadow-sm" v-if="is_admin">
                <table>
                    <tr>
                        <td class="text-danger">
                            <span>Remove from CareTeam</span>
                        </td>
                        <td align="right">
                            <button type="button" id="deleteMember" class="btn btn-danger text-white" href="#!" @click="deleteMember();" :disabled="member.role == 'admin'">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </td>
                    </tr>
                </table>
            </div>

            <div class=" p-3 centered">
                <input type="hidden" name="id" id="id" required v-model="member.id">
                <a class="btn btn-primary centered mb-2" href="{{route('careteam', $loveone->slug)}}">Return to CareTeam</a>
                <button class="btn btn-primary loadingBtn mr-2 mb-2" type="submit" data-loading-text="Saving..." id="savePermissionsBtn"  v-if="is_admin" :disabled="member.role == 'admin'">Save changes</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('css')
@endpush



@push('scripts')
<script>
$(function(){
});

const careteam = new Vue ({
    el: '#careteam',
    created: function() {
        console.log('careteam');
        $('#editMemberForm').removeClass('d-none');
        $('.loading').addClass('d-none');
    },
    data: {
        member: {
            loveone_id: {{$loveone->id}},
            name: '{{$member->name}}',
            lastname: '{{$member->lastname}}',
            email: '{{$member->email}}',
            phone: '{{$member->phone}}',
            address: '{{$member->address}}',
            photo: '{{$member->photo}}',
            photo: '{{$member->photo}}',
            role: '{{$member->permissions->role}}',
            id: {{$member->id}},
            permissions: {
                carehub: {{ $member->permissions->permissions['carehub']}},
                lockbox: {{ $member->permissions->permissions['lockbox']}},
                medlist: {{ $member->permissions->permissions['medlist']}},
                resources: {{ $member->permissions->permissions['resources']}},
            },
        },
        action: '',
        is_admin: {{ (Auth::user()->permissions->role == 'admin') ? 'true' : 'false'}},
    },
    filters: {
        mayuscula: function (value) {
            if (!value) return ''
            value = value.toString();
            return value.toUpperCase(); 
        },
        formatPhoneNumber: function(phoneNumberString) {
            var cleaned = ('' + phoneNumberString).replace(/\D/g, '');
            var match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
            if (match) {
                return '(' + match[1] + ') ' + match[2] + '-' + match[3];
            }
            return null;
        }
    },
    computed:{ 
    },
    methods: {
        
        saveMemberPermissions: function() {

            // console.log('saving permissions');
            $('#savePermissionsBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('#savePermissionsBtn').data('loading-text')).attr('disabled', true);              

            var url = '{{ route("careteam.updateMemberPermissions") }}';
            
            axios.post(url, this.member)
            .then(response => {
                console.log(response.data);
                
                if(response.data.success){
                    $('#editMemberModal').modal('hide');
                    msg = 'The member was edited successfully.';
                    icon = 'success';
                    
                } else {
                    msg = 'There was an error. Please try again. Error: ' + response.data.error;
                    icon = 'error';
                }
                swal(msg, "", icon);
                $('#savePermissionsBtn').html('Save').attr('disabled', false);

                
            }).catch( error => {
                console.log(error);
                msg = 'There was an error editing the member permissions. Please try again. Error: ' + error;
                swal('Error', msg, 'error');
                $('#savePermissionsBtn').html('Save').attr('disabled', false);
            });
        },
        editMember: function(member_id) {
            $('#editMemberForm #member_id').val(member_id);
            $('#editMemberForm').submit();
        },
        deleteMember: function() {

            swal({
                title: "Warning",
                text: "Are you sure delete this member?",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    "Yes, I'm sure!"
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                console.log(isConfirm);
                if (isConfirm) {

                    $('#deleteMember').html('<i class="fas fa-trash-alt"></i> Deleting...').attr('disabled', true);
                    var url = '{{ route("careteam.deleteMember") }}';
                    data = {
                        loveoneId: careteam.member.loveone_id,
                        memberId: careteam.member.id
                    };
                    
                    axios.post(url, data)
                    .then(response => {
                        // console.log(response.data.success);
                        
                        if(response.data.success){
                            $('#editMemberModal').modal('hide');
                            msg = 'The member was deleted from the Careteam.';
                            icon = 'success';
                            
                        } else {
                            msg = 'There was an error. Please try again. Error: ' + response.data.error;
                            icon = 'error';
                        }
                        swal(msg, "", icon);
                        // $('#deleteMember').html('<i class="fas fa-trash-alt"></i> Delete').attr('disabled', false);
                        window.location.href="{{ route('careteam', [$loveone->slug] )}}";

                        
                    }).catch( error => {
                        console.log(error);
                        msg = 'There was an error editing the member permissions. Please try again. Error: ' + error;
                        swal('Error', msg, 'error');
                        $('#deleteMember').html('Save').attr('disabled', false);
                    });

                } 
            });
        },
    }
});
</script>
@endpush
