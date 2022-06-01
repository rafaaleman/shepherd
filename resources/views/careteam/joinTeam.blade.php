@extends('layouts.app_simple')

@section('content')
<div class="container-fluid" id="joinTeam">
    <div class="row justify-content-center">

        <div class="col-md-6 left-side bg-primary d-none d-md-flex align-items-center ">
            <div class="bigBtn">
                <i class="fas fa-users mb-1" style="font-size: 100px"></i> <br>
                Join an existing CareTeam
            </div>
        </div>

        <div class="col-sm-12 col-md-6 mb-5">

            <div class="p-5">Pending invitations:</div>
            <div class="row justify-content-center d-flex">
                <div class="col-md-10">
                    <div class="card shadow-sm">

                        <div class="card-body mb-3">

                            <form method="post" id="" class="col-md-12">

                                <div class="section my-5">
                                    <table width="100%" class="invitations" v-if="invitations">
                                        <tr v-for="invitation in invitations">
                                            <td>
                                                <img :src="invitation.loveone.photo" class="photo">
                                            </td>
                                            <td>
                                                <strong>@{{ invitation.loveone.firstname }} @{{ invitation.loveone.lastname }}</strong>
                                                <div class="role">@{{ invitation.role | mayuscula }}</div>
                                            </td>
                                            <td align="right" class=""text-nowrap>
                                                <div class="custom-control custom-switch">
                                                    <button class="btn btn-primary btn-sm" @click.prevent="acceptInvitation({{Auth::user()->id}}, invitation.token)">Accept</button>
                                                    <button class="btn btn-danger btn-sm" @click.prevent="declineInvitation( invitation.token)">Decline</button>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </table>
                                    <h4 v-else>
                                        No pending invitations
                                    </h4>

                                </div>
                                {{-- <input type="hidden" name="id" id="id" required >
                                <button class="btn btn-primary loadingBtn btn-lg my-4" type="submit" data-loading-text="Wait..." id="includeMember">Accept invitations</button> --}}
                            </form>
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('home') }}" class="mt-3 btn btn-secondary">Go to Carehub</a>
                    </div>
                </div>    
            </div>
            
        </div>
        
    </div>
</div>
@endsection

@push('styles')
<style>

    .top-navigation{
        margin-bottom: 0 !important;
    }
    .top-bar{
        display: none !important;
    }

    .left-side{
        height: 95vh;
    }

</style>

@endpush

@push('scripts')
<script>

    const joinTeam = new Vue ({
        el: '#joinTeam',
        created: function() {
            console.log('joinTeam');
            this.getInvitations();
        },
        data: {
            loveone_id : '',
            invitations: '',
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
            getInvitations: function() {

                var url = '{{ route("careteam.getInvitations") }}';
                axios.get(url).then(response => {
                    console.log(response.data);
                    
                    if( response.data.invitations.length > 0 ){
                        this.invitations = response.data.invitations;
                        
                    } else {
                        // msg = 'There are no pending invitations';
                        // icon = 'success';
                        // swal(msg, "", icon);
                        window.location.href = '{{route("home")}}';
                    }
                    
                });
            },
            acceptInvitation: function(user_id, token) {

                // swal({
                //     title: "Warning",
                //     text: "Are you sure accept this invitation?",
                //     icon: "warning",
                //     buttons: [
                //         'No, cancel it!',
                //         "Yes, I'm sure!"
                //     ],
                //     dangerMode: true,
                // }).then(function(isConfirm) {

                //     if(isConfirm){
                        var url = '{{ route("careteam.acceptInvitation") }}';
                        data = {
                            user_id: user_id,
                            token: token
                        };
                        axios.post(url, data).then(response => {
                            console.log(response.data);
                            
                            if( response.data.success == true ){
                                joinTeam.getInvitations();
                                msg = 'The invitation was accepted';
                                icon = 'success';
                                
                            } else {
                                msg = 'There was an error. Please try again';
                                icon = 'error';
                            }
                            
                            swal(msg, "", icon);
                        });
                //     }
                // });
            },
            declineInvitation: function(token) {

                swal({
                    title: "Warning",
                    text: "Are you sure decline this invitation?",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        "Yes, I'm sure!"
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {

                    if(isConfirm){
                        var url = '{{ route("careteam.declineInvitation") }}';
                        data = {
                            token: token
                        };
                        axios.post(url, data).then(response => {
                            console.log(response.data);
                            
                            if( response.data.success == true ){
                                joinTeam.getInvitations();
                                msg = 'The invitation was declined';
                                icon = 'success';
                                
                            } else {
                                msg = 'There was an error. Please try again';
                                icon = 'error';
                            }
                            
                            swal(msg, "", icon);
                        });
                    }
                });
            }
        },
    });


    
</script>
@endpush
