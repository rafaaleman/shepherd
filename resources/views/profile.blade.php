@extends('layouts.app')

@section('content')
<div class="container" id="profile">
    <form method="POST" action="" enctype="multipart/form-data" id="register_user" v-on:submit.prevent="updateUser()" class="row justify-content-center mb-5">
        @csrf
        <div class="col-md-5">

            <div class="form-group row">
                {{-- <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label> --}}
                <div class="col-md-12 d-flex justify-content-center mb-3 photo-container shadow-sm" style="background-image: url('{{ Auth::user()->photo }}');">
                    <div class="bigBtn" style="{{ (Auth::user()->photo) ? 'justify-content: flex-end;' : 'justify-content: center;'}}">
                        @if (empty(Auth::user()->photo))
                            <i class="far fa-user mb-1" style="font-size: 100px"></i> <br>
                            Click To Upload Photo
                        @else
                            Click To Change Photo
                        @endif
                    </div>
                    <input id="photo" type="file" class="d-none form-control" name="photo" accept=".jpg, .png, .jpeg" v-on:change="onFileChange">
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-7">
                            <input id="name" type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required autocomplete="name" autofocus v-model="user.name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="lastname" class="col-md-4 col-form-label text-md-right">Lastname</label>

                        <div class="col-md-7">
                            <input id="lastname" type="text" class="form-control" name="lastname" value="{{ Auth::user()->lastname }}" required autocomplete="lastname" v-model="user.lastname">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-7">
                            <input id="email" type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required autocomplete="email" {{isset(Auth::user()->email) ? 'readonly' : ''}} v-model="user.email">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="phone" class="col-md-4 col-form-label text-md-right">Phone Number</label>

                        <div class="col-md-7">
                            <input id="phone" type="tel" class="form-control" name="phone" value="{{ Auth::user()->phone }}" autocomplete="phone" v-model="user.phone">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>

                        <div class="col-md-7">
                            <input id="address" type="text" class="form-control" name="address" value="{{ Auth::user()->address }}" autocomplete="address"  v-model="user.address">
                        </div>
                    </div>

                    <div class="form-group row mt-2 mb-0">
                        <i class="text-black-50 p-4" style="font-size: 12px">Leave blank if you want to preserve your current password</i> 
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                        <div class="col-md-7">
                            <input id="password" type="password" class="form-control" name="password" autocomplete="new-password" v-model="user.password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Passw.</label>

                        <div class="col-md-7">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" v-model="user.password_confirmation">
                        </div>
                    </div>
                
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <button type="submit" class="btn btn-primary btn-lg update mt-3">Save</button>
        </div>
    </form>

    <h5 class="">Your Loved Ones</h5>
    <div class="col-md-12 mb-5 p-0">
        <div class="card">
            <div class="card-body loveones shadow-sm">
                @foreach ($loveones as $loveone)
                    <div class="loveone p-2 mb-3">
                        <div style="background-image: url('{{asset($loveone->photo)}}');" class="float-left mr-3 photo"></div>
                        <div class="data float-left">
                            <div class="name">{{$loveone->firstname}} {{$loveone->lastname}} <i class="text-danger"></i></div>
                            <small>{{ucfirst($loveone->careteam->role)}}</small>
                        </div>
                        
                        <div class="custom-control custom-switch float-right mt-3">
                            <input type="checkbox" class="custom-control-input checkbox" id="loveone{{$loveone->id}}" {{($loveone->careteam->status == 1) ? 'checked' : ''}} value="{{$loveone->id}}">
                            <label class="custom-control-label" for="loveone{{$loveone->id}}"></label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <h5 class="">Pending Invitations</h5>
    <form method="post" id="" class="col-md-12 mb-3 p-0">

        <div class="card">
            <div class="card-body invitations shadow-sm">
                <table width="100%" class="invitations" v-if="invitations">
                    <tr v-for="invitation in invitations">
                        <td>
                            <img :src="invitation.loveone.photo" class="photo" width="100">
                        </td>
                        <td>
                            <strong>@{{ invitation.loveone.firstname }} @{{ invitation.loveone.lastname }}</strong>
                            <div class="role">@{{ invitation.role | mayuscula }}</div>
                        </td>
                        <td align="right" width="60px">
                            <div class="custom-control custom-switch">
                                <button class="btn btn-primary" @click.prevent="acceptInvitation({{Auth::user()->id}}, invitation.token)">Accept</button>
                            </div>
                        </td>
                    </tr>
                    
                </table>
                <h4 v-else>
                    No pending invitations
                </h4>
            </div>
        </div>
        {{-- <input type="hidden" name="id" id="id" required >
        <button class="btn btn-primary loadingBtn btn-lg my-4" type="submit" data-loading-text="Wait..." id="includeMember">Accept invitations</button> --}}
    </form>

</div>
@endsection

@push('styles')
<style>

.loveone{
    background-color: #f8fafc;
    overflow: hidden;
    border-radius: 10px;
}
.loveone .photo{
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 20px;
    background-size: cover;
    background-position: center;
    background-color: azure;
}

.photo-container{
    padding: 0;

}

.photo-container .bigBtn {
    background: rgb(49, 133, 152);
    background: linear-gradient(0deg, rgba(49, 133, 152, 0.8491771709) 0%, rgba(49, 133, 152, 0.7763480392) 9%, rgba(49, 133, 152, 0) 17%);
}
</style>
@endpush

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
<script>

$(function(){

    new Cleave('#phone', {
        numericOnly: true,
        blocks: [0, 3, 0, 3, 4],
        delimiters: ["(", ")", " ", "-"]
    });

    $('.bigBtn').click(function(){
        $(' #photo').click();
    });

    $('.loveone .checkbox').change(function(){
        
        checkbox = $(this);
        checked = $(this).prop("checked");
        if(checked){
            //enable
            checkbox.parents('.loveone').find('.name i').text('Wait...');
            changeStatus(checkbox.val(), checked);

        } else {
            // Disable
            swal({
                title: "Warning",
                text: "Are you sure disable all the notifications?",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    "Yes, I'm sure!"
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                console.log(isConfirm);
                if (isConfirm) {

                    checkbox.parents('.loveone').find('.name i').text('Wait...');
                    changeStatus(checkbox.val(), checked);
                    
                } else {
                    checkbox.prop("checked", true);
                }
            });
        }
    });

    function changeStatus(loveone_id, status){
        var url = '{{ route("careteam.changeStatus") }}';
        data = {
            loveoneId: loveone_id,
            userId: {{ Auth::user()->id }},
            status: status
        };
        
        axios.post(url, data)
        .then(response => {
            // console.log(response.data.success);
            
            if(response.data.success){
                txt = (status) ? 'enabled' : 'disabled';
                msg = 'The loveone was '+txt+' successfully.';
                icon = 'success';
                
            } else {
                msg = 'There was an error. Please try again.';
                icon = 'error';
            }
            swal(msg, "", icon);    
            $('.loveone .name i').text('');        
        });
    }
});

const profile = new Vue ({
    el: '#profile',
    created: function() {
        console.log('profile');
        this.getInvitations();
    },
    data: {
        user: {
            name    : "{{ Auth()->user()->name ?? '' }}",
            lastname: "{{ Auth()->user()->lastname ?? '' }}",
            email   : "{{ Auth()->user()->email ?? '' }}",
            phone   : "{{ Auth()->user()->phone ?? '' }}",
            address : "{{ Auth()->user()->address ?? '' }}",
            photo   : "",
            password: "",
            password_confirmation: '',
        },
        invitations: '',
    },
    filters: {
    },
    computed:{ 
    },
    methods: {
        updateUser: function() {

            if(this.user.password != '' && this.user.password != this.user.password_confirmation){
                swal('Error', "The passwords don't match, please verify.", 'error');
                return;
            }

            console.log('updating');
            $('.btn.update').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Saving...').attr('disabled', true);
            this.user.phone = this.user.phone.replace(/\D/g,'');

            const config = {
                headers: { 
                    'content-type': 'multipart/form-data',
                }
            }
            let formData = new FormData();
            if(this.user.photo)
                formData.append('file', this.user.photo);
            
            var user = JSON.stringify(this.user);
            // console.log(user);
            formData.append('user', user);

            var url = '{{ route("user.profile.update") }}';
            axios.post(url, formData, config)
            .then(response => {
                console.log(response.data);
                
                if(response.data.success){
                    if(response.data.photo != '')
                        profile.user.photo = response.data.photo; 
                    
                    swal('Your user was updated successfully!', "", 'success');  
                    
                } else {
                    
                    swal('There was an error.', response.data.msg, 'error');
                }
                
                $('.btn.update').html('Save').attr('disabled', false);
                
                    
            });
        },
        onFileChange(e){
            console.log(e.target.files[0]);
            this.user.photo = e.target.files[0];

            var file = $('#photo');
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.photo-container').css('background-image', 'url('+e.target.result+')');
            }
            reader.readAsDataURL(file[0].files[0]);
        },
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
                }
                
            });
        },
        acceptInvitation: function(user_id, token) {

            swal({
                title: "Warning",
                text: "Are you sure accept this invitation?",
                icon: "warning",
                buttons: [
                    'No, cancel it!',
                    "Yes, I'm sure!"
                ],
                dangerMode: true,
            }).then(function(isConfirm) {

                if(isConfirm){
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
                }
            });
        }
    },
});


    
</script>
@endpush
