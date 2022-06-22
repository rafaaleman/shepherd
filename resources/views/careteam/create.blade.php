@extends('layouts.app')

@section('content')
<div class="container"  id="careteam">
            
    <div class="row">

        <form method="post" id="searchMemberForm" class="col-md-12 p-3" v-on:submit.prevent="searchMember()">

            <h5 class="mb-4">Invite Someone to <span id="loveoneName"></span>'s CareTeam</h5>
            
            <label for="s_email" class="text-black-50">Enter invitee's email:</label>
            <div class="d-flex mb-1">
                <input id="s_email" type="email" class="form-control mr-2" name="s_email" autofocus required>
                <button class="btn btn-primary searchBtn text-nowrap">Set Role and Permissions </button>
            </div>
        </form>

        <form method="post" id="inviteMemberForm__" class="col-md-12 px-3 d-none" v-on:submit.prevent="inviteMember()">
            <div class="col text-center">
                <p class="text-danger">Configure role and permissions to send an invite.</p>
            </div>            
        </form>

        <form method="post" id="inlcudeMemberForm" class="col-md-12 p-3 d-none" v-on:submit.prevent="addMember2Careteam()">

            <div class="section white-section shadow-sm row p-3">
                
                <div class="col-md-12 d-flex row">
                    <div class="col-sm-12 col-md-6" >
                        <div class="mt-2"> Role:</div>
                        <select name="role" id="role" v-model="member.role_id" class="form-control" :disabled="(member.role_id == 'admin')" required>
                            @foreach ($roles as $key => $role)
                                <option value="{{$key}}" {{ ($key == 'admin') ? 'disabled' : ''}}>{{$role}}</option>
                            @endforeach
                        </select>
                    </div>
                
                    <div class="col-sm-12 col-md-6">
                        <div class="mt-2"> Relationship:</div>
                        <select name="relationship" id="relationship" v-model="member.relationship_id" class="form-control" required>
                            @foreach ($relationships as $relationship)
                                <option value="{{$relationship->id}}">{{$relationship->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-5 col-md-12"> Toggle to choose which features you want the new Care Team member to have access to:</div>
                
                <table class="mt-4 col-md-12 table w-100">
                    <tr>
                        <td class="carehub pt-4 text-primary">
                            <i class="mr-3 far fa-calendar-plus"></i> 
                            <span>CarePoints</span>
                        </td>
                        <td align="right">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="carehub" v-model="member.permissions.carehub">
                                <label class="custom-control-label" for="carehub"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="lockbox pt-4 text-primary">
                            <i class="mr-3 fas fa-file-medical"></i> 
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
                        <td class="medlist pt-4 text-primary">
                            <i class="mr-3 fas fa-prescription-bottle-alt"></i> 
                            <span>MedList</span>
                        </td>
                        <td align="right">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="medlist" v-model="member.permissions.medlist">
                                <label class="custom-control-label" for="medlist"></label>
                            </div>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td class="resources pt-4 text-primary">
                            <i class="mr-3 fas fa-globe"></i> 
                            <span>Resources</span>
                        </td>
                        <td align="right">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="resources" v-model="member.permissions.resources">
                                <label class="custom-control-label" for="resources"></label>
                            </div>
                        </td>
                    </tr> --}}
                    

                </table>
            </div>
            <div class="col text-center">
                <input type="hidden" name="id" id="id" required v-model="member.id">
                <button class="btn btn-primary loadingBtn m-4 centered" type="submit" data-loading-text="Sending..." id="includeMember">Send an Invitation</button>
            </div>
        </form>
        
        <form method="post" id="inviteMemberForm" class="col-md-12 px-3 d-none" v-on:submit.prevent="inviteMember()">
            <div class="col text-center">
                <a class="btn btn-primary centered" href="#!" class="text-black-50" id="sendLink" v-on:click.prevent="sendInvitation()">Send an Invitation</a>
            </div>            
        </form>
        <a class="btn btn-primary loadingBtn my-2 centered" href="{{route('careteam', $loveone->slug)}}">Return to CareTeam</a>
    </div>
</div>
@endsection


@push('scripts')
<script>
$(function(){
    
    loveone = localStorage.getItem('loveone');
    loveone = JSON.parse(loveone);
    $('#loveoneName').text(loveone.firstname)
});

const careteam = new Vue ({
    el: '#careteam',
    created: function() {
        console.log('careteam');
    },
    data: {
        member: {
            loveone_id: '{{$loveone->id}}',
            name: '',
            lastname:'',
            email: '',
            phone: '',
            address: '',
            id: 0,
            permissions: {
                carehub: 0,
                lockbox: 0,
                medlist: 0,
                resources: 0,
            },
        },
        action: '',
        is_admin: false,
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
        // Search if the email/user is already on Shepheard
        searchMember: function() {
            
            // console.log('saving member');
            $('.searchBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>').attr('disabled', true);

            var url = '{{ route( "careteam.searchMember") }}';
            data = {
                email: $('#s_email').val(),
                loveone_id: this.member.loveone_id
            };

            axios.post(url, data)
            .then(response => {
                // console.log(response.data);
                if(response.data.user === 2){ // Already in careteam

                    msg = 'The user is already in the CareTeam';
                    icon = 'warning';
                    swal(msg, "", icon);
                    $('.searchBtn').html('<i class="fas fa-search"></i>').attr('disabled', false).removeClass('disabled');

                } else if(response.data.user){ // Find an existing user but no in careteam
                    careteam.member.id = response.data.user.id;
                    careteam.member.name = response.data.user.name;
                    careteam.member.lastname = response.data.user.lastname;
                    careteam.member.email = response.data.user.email;
                    careteam.member.permissions = {
                        carehub : false,
                        lockbox : false,
                        medlist : false,
                        resources : false,
                    }

                    if(response.data.user.photo == '')
                        $('.searchBtn').html('<i class="fas fa-user-check"></i>').attr('disabled', true).removeClass('btn-primary').addClass('btn-success');
                    else
                        $('.searchBtn').html('<img src="'+ response.data.user.photo +'" style="height:35px" class="rounded-pill">').attr('disabled', true).removeClass('btn-primary');

                    $('#s_email').attr('disabled', true).val(response.data.user.name + ' ' + response.data.user.lastname + ' ('+response.data.user.email+')');
                    $('#inlcudeMemberForm').removeClass('d-none');


                    
                } else { // External non register user
                    careteam.member.email = $('#s_email').val();
                    careteam.member.permissions = {
                        carehub : false,
                        lockbox : false,
                        medlist : false,
                        resources : false,
                    };

                    $('.searchBtn').html('<i class="fas fa-search"></i>').attr('disabled', false).removeClass('disabled');
                    $('#inviteMemberForm, #inlcudeMemberForm').removeClass('d-none');
                    $('#includeMember').addClass('d-none')
                }

                
            }).catch( error => {
                // console.log(error);
                msg = 'There was an error searching the new member. Error: ' + error;
                swal('Error', msg, 'error');
                $('.searchBtn').html('<i class="fas fa-search"></i>').attr('disabled', false).removeClass('disabled');
            });
        },
        // send invitation to a already Shepherd user
        addMember2Careteam: function() {

            // console.log('saving permissions');
            $('#includeMember').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('#includeMember').data('loading-text')).attr('disabled', true);              

            var url = '{{ route("careteam.inlcudeAMember") }}';
            
            axios.post(url, this.member)
            .then(response => {
                // console.log(response.data);
                
                if(response.data.success){
                    $('#inviteMemberModal').modal('hide');
                    msg = 'The member was invited to the team.';
                    icon = 'success';
                    
                } else {
                    msg = 'There was an error. Please try again. Error: ' + response.data.error;
                    icon = 'error';
                }
                swal(msg, "", icon);
                // $('#s_email').val('').attr('disabled', false);
                $('#includeMember').html('Save').attr('disabled', false);

                
            }).catch( error => {
                console.log(error);
                msg = 'There was an error editing the member permissions. Please try again. Error: ' + error;
                swal('Error', msg, 'error');
                $('#includeMember').html('Save').attr('disabled', false);
            });
        },
        // send invitation to a possible Shepherd user
        sendInvitation: function() {

            // console.log('saving permissions');
            $('#sendLink').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Sending... ').attr('disabled', true);              

            var url = '{{ route("careteam.sendInvitation") }}';
            data = {
                email: $('#s_email').val(),
                loveone_id: this.member.loveone_id,
                permissions: this.member.permissions,
                role_id: this.member.role_id,
                relationship_id: this.member.relationship_id
            };
            
            axios.post(url, data)
            .then(response => {
                // console.log(response.data);
                
                if(response.data.success){
                    // this.getCareteamMembers();
                    // $('#inviteMemberModal').modal('hide');
                    msg = 'The member was invited successfully.';
                    icon = 'success';
                    
                } else {
                    msg = 'There was an error. Please try again. Error: ' + response.data.error;
                    icon = 'error';
                }
                swal(msg, "", icon);
                // $('#s_email').val('').attr('disabled', false);
                $('#sendLink').html('Send an invite').attr('disabled', false);

                
            }).catch( error => {
                console.log(error);
                msg = 'There was an error. Please try again. Error: ' + error;
                swal('Error', msg, 'error');
                $('#sendLink').html('Send an invite').attr('disabled', false);
            });
        },
        
    }
});
</script>
@endpush
