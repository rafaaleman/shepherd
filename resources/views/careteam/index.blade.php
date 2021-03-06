@extends('layouts.app')

@section('content')
<div class="container"  id="careteam">

    <a href="{{route('careteam.createNewMember', [$loveone_slug])}}" class="newMember btn btn-primary mb-3" v-if="is_admin">
        Invite a New Member
    </a>

    <div class="loading">
        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading members...
    </div>
    
    <div class="members d-flex flex-wrap shadow-sm p-4">
    
        <div class="card mm p-3 border mb-2 mr-2" v-for="member in members">
            <div class="card-body_">
                
                <a class="" href="#" @click.prevent="editMember(member.id)">
                    <div class="member d-flex">
                        <img :src="member.photo" class=" mr-2">
                        <div class="data">
                            <i class="fas fa-info-circle fa-2x mt-2 info mr-2 float-right"></i>
                            <div class="name">@{{ member.name }} @{{ member.lastname }}</div>
                            <div class="role">@{{ (member.careteam.role == 'admin') ? 'Leader' : member.careteam.role | mayuscula }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card  mm p-3 border mb-2 mr-2" v-for="invitation in invitations">
            <div class="member">
                <div class="d-flex">
                    <img src="{{asset('/img/no-avatar.png')}}" class= mr-2">
                    <div class="data ml-2">
                        <div class="name">@{{ invitation.email }}</div>
                        {{-- <div class="role">@{{ invitation.role | mayuscula }}</div> --}}
                        <div class="d-block">
                            <i class="fas fa-times-circle text-danger" @click="deleteInvitation(invitation.id)"></i>
                            <i class="info mr-2 text-danger">Invitation sent</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Form to edit members --}}
<form action="{{ route('careteam.editMember', $loveone->slug) }}" method="POST" id="editMemberForm">
    @csrf
    <input type="hidden" name="member_id" id="member_id">
    <input type="hidden" name="loveoned_id" value="{{$loveone->id}}">
</form>

@endsection

@push('styles')
<style>
    
</style>
@endpush

@push('scripts')
<script>

$(function(){

    // if(window.location.hash == '#addMember') {
        
    //     $('#inviteMemberModal').modal('show');
    //     careteam.changeAction('CREATE', '');
    // } 
});

    const careteam = new Vue ({
        el: '#careteam',
        created: function() {
            console.log('careteam');
            this.getCareteamMembers();
        },
        data: {
            members: [],
            invitations: [],
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
            createLoveone: function() {
                console.log('creating');
                $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                this.loveone.phone = this.loveone.phone.replace(/\D/g,'');

                var url = '{{ route("loveone.create") }}';
                axios.post(url, this.loveone).then(response => {
                    console.log(response.data);
                    
                    if(response.data.success){
                        msg  = 'Your Loveone was saved successfully!';
                        icon = 'success';
                        this.loveone.id = response.data.data.loveone.id; 
                    } else {
                        msg = 'There was an error. Please try again';
                        icon = 'error';
                    }
                    
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    swal(msg, "", icon);
                        
                }).catch( error => {
                    
                    txt = "";
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    $.each( error.response.data.errors, function( key, error ) {
                        txt += error + '\n';
                    });
                    
                    swal('There was an Error', txt, 'error');
                });
            }, 
            getCareteamMembers: function() {
                
                console.log('getting members of {{$loveone_slug}}');  
                $('.loading').show();              

                var url = '{{ route("careteam.getCareteamMembers") }}';
                axios.post(url, { loveone_slug: '{{$loveone_slug}}' }).then(response => {
                    // console.log(response.data);
                    
                    if(response.data.success){
                        this.members     = response.data.data.members;
                        this.invitations = response.data.data.invitations;
                        this.is_admin    = response.data.data.is_admin;
                    } else {
                        msg = 'There was an error. Please try again';
                        icon = 'error';
                        swal(msg, "", icon);
                    }
                    
                    $('.loading').hide();
                    
                }).catch( error => {
                    
                    msg = 'There was an error getting careteam members. Please reload the page';
                    swal('Error', msg, 'error');
                });
            },
            changeAction: function(action, member) {

                // console.log(member);
                this.action = action;
                
                if(this.action == 'CREATE'){

                    this.member = {
                        loveone_id: '{{$loveone->id}}',
                        name: '',
                        lastname:'',
                        email: '',
                        phone: '',
                        address: '',
                        password: '',
                        id: 0,
                        permissions: '',
                        photo: '',
                    };
                } else {

                    this.member = {
                        loveone_id: '{{$loveone->id}}',
                        name: member.name,
                        lastname: member.lastname,
                        email: member.email,
                        phone: member.phone,
                        address: member.address,
                        password: '',
                        photo: member.photo,
                        role_id: member.careteam.role,
                        id: member.id,
                        permissions: member.careteam.permissions,
                    };
                }

                loveoneName = JSON.parse(localStorage.getItem('loveone')).firstname;
                $('#loveoneName').text(loveoneName);
            },
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

                        msg = 'The user is already in the careteam';
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

                        $('.searchBtn').html('<i class="fas fa-user-check"></i>').attr('disabled', true).removeClass('btn-primary').addClass('btn-success');
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
                        this.getCareteamMembers();
                        
                    } else {
                        msg = 'There was an error. Please try again. Error: ' + response.data.error;
                        icon = 'error';
                    }
                    swal(msg, "", icon);
                    $('#includeMember').html('Save').attr('disabled', false);

                    
                }).catch( error => {
                    console.log(error);
                    msg = 'There was an error editing the member permissions. Please try again. Error: ' + error;
                    swal('Error', msg, 'error');
                    $('#includeMember').html('Save').attr('disabled', false);
                });
            },
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
                        this.getCareteamMembers();
                        $('#inviteMemberModal').modal('hide');
                        msg = 'The member was invited successfully.';
                        icon = 'success';
                        
                    } else {
                        msg = 'There was an error. Please try again. Error: ' + response.data.error;
                        icon = 'error';
                    }
                    swal(msg, "", icon);
                    $('#sendLink').html('Send an invite').attr('disabled', false);

                    
                }).catch( error => {
                    console.log(error);
                    msg = 'There was an error. Please try again. Error: ' + error;
                    swal('Error', msg, 'error');
                    $('#sendLink').html('Send an invite').attr('disabled', false);
                });
            },
            saveMemberPermissions: function() {

                // console.log('saving permissions');
                $('#savePermissionsBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('#savePermissionsBtn').data('loading-text')).attr('disabled', true);              

                var url = '{{ route("careteam.updateMemberPermissions") }}';
                
                axios.post(url, this.member)
                .then(response => {
                    // console.log(response.data);
                    
                    if(response.data.success){
                        $('#editMemberModal').modal('hide');
                        msg = 'The member was edited successfully.';
                        icon = 'success';
                        this.getCareteamMembers();
                        
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
            onFileChange(e){
                console.log(e.target.files[0]);
                this.member.photo = e.target.files[0];
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
                                careteam.getCareteamMembers();
                                
                            } else {
                                msg = 'There was an error. Please try again. Error: ' + response.data.error;
                                icon = 'error';
                            }
                            swal(msg, "", icon);
                            $('#deleteMember').html('<i class="fas fa-trash-alt"></i> Delete').attr('disabled', false);

                            
                        }).catch( error => {
                            console.log(error);
                            msg = 'There was an error editing the member permissions. Please try again. Error: ' + error;
                            swal('Error', msg, 'error');
                            $('#deleteMember').html('Save').attr('disabled', false);
                        });





                    } 
                });
            },
            deleteInvitation: function(invitation_id) {
                swal({
                    title: "Warning",
                    text: "Are you sure delete this invitation?",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        "Yes, I'm sure!"
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {

                        var url = '{{ route("careteam.deleteInvitation") }}';
                        data = {
                            invitationId: invitation_id,
                        };
                        
                        axios.post(url, data)
                        .then(response => {
                            // console.log(response.data.success);
                            
                            if(response.data.success){
                                msg = 'The invitation was deleted.';
                                icon = 'success';
                                careteam.getCareteamMembers();
                                
                            } else {
                                msg = 'There was an error. Please try again. Error: ' + response.data.error;
                                icon = 'error';
                            }
                            swal(msg, "", icon);

                            
                        }).catch( error => {
                            console.log(error);
                            msg = 'There was an error. Please try again. Error: ' + error;
                            swal('Error', msg, 'error');
                        });
                    } 
                });
            },
            editMember: function(member_id) {
                $('#editMemberForm #member_id').val(member_id);
                $('#editMemberForm').submit();
            }
        }
    });
    
</script>
@endpush