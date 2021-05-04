@extends('layouts.app')

@section('content')
<div class="container"  id="careteam">
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{$loveone->firstname}} {{$loveone->lastname}}</h5>
            <p class="card-text">Loveone</p>
            

            <div class="row">
                <div class="col-md-6">
                    <img src="{{ (!empty($loveone->photo) && $loveone->photo != null ) ? $loveone->photo : asset('public/img/no-avatar.png')}}" class="img-fluid">
                </div>
                
                <div class="col-md-6 ">
                    
                    <a href="#!" data-toggle="modal" data-target="#inviteMemberModal" class="newMember">
                        <div class="member d-flex add">
                            <i class="fas fa-plus-circle fa-3x mr-2"></i>
                            <div class="data">
                                <div class="name mt-2" @click="changeAction('CREATE', '')">Invite a New Member</div>
                            </div>
                        </div>
                    </a>

                    <div class="loading">
                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading members...
                    </div>

                    <div class="members">

                        <a v-for="member in members" class="" href="#" data-toggle="modal" data-target="#editMemberModal" >
                            <div class="member" @click="changeAction('EDIT', member)">
                                <img :src="member.photo" class="float-left mr-3">
                                <div class="data float-left">
                                    <div class="name">@{{ member.name }} @{{ member.lastname }}</div>
                                    <div class="role">@{{ member.careteam.role | mayuscula }}</div>
                                </div>
                                
                                <i class="fas fa-info-circle fa-2x mt-2 info float-right mr-2"></i>
                                
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('careteam.create_modal')
    @include('careteam.edit_modal')
</div>
@endsection

@push('styles')
<style>
    
</style>
@endpush

@push('scripts')
<script>

    const careteam = new Vue ({
        el: '#careteam',
        created: function() {
            console.log('careteam');
            this.getCareteamMembers();
        },
        data: {
            members: [],
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
                
                // console.log('getting members');  
                $('.loading').show();              

                var url = '{{ route("careteam.getCareteamMembers", "*SLUG*") }}';
                url = url.replace('*SLUG*', '{{$loveone_slug}}');
                axios.get(url).then(response => {
                    // console.log(response.data);
                    
                    if(response.data.success){
                        this.members = response.data.data.members; 
                        this.is_admin = response.data.data.is_admin;
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
                    console.log(response.data);
                    if(response.data.user === 2){

                        msg = 'The user is already in the careteam';
                        icon = 'warning';
                        swal(msg, "", icon);
                        $('.searchBtn').html('<i class="fas fa-search"></i>').attr('disabled', false).removeClass('disabled');

                    } else if(response.data.user){
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
                        $('#inviteMemberForm .section, #includeMember').removeClass('d-none');


                        
                    } else {
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
                        msg = 'The member was inluded successfully.';
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
                        'Yes, I am sure!'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
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
        }
    });
    
</script>
@endpush