@extends('layouts.app')

@section('content')
<div class="container" id="profile">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">Profile: </div>

                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data" id="register_user" v-on:submit.prevent="updateUser()">
                        @csrf

                        <div class="form-group row">
                            {{-- <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label> --}}
                            <div class="col-md-12 d-flex justify-content-center mb-3" style="background-image: url('{{ Auth::user()->photo }}'); background-size:cover;">
                                <div class="bigBtn">
                                    <i class="far fa-user mb-1" style="font-size: 100px"></i> <br>
                                    Upload Photo
                                </div>
                                <input id="photo" type="file" class="d-none form-control" name="photo" accept=".jpg, .png" v-on:change="onFileChange">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required autocomplete="name" autofocus v-model="user.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lastname" class="col-md-4 col-form-label text-md-right">Lastname</label>

                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control" name="lastname" value="{{ Auth::user()->lastname }}" required autocomplete="lastname" v-model="user.lastname">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required autocomplete="email" {{isset($email) ? 'readonly' : ''}} v-model="user.email">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">Phone Number</label>

                            <div class="col-md-6">
                                <input id="phone" type="tel" class="form-control" name="phone" value="{{ Auth::user()->phone }}" required autocomplete="phone" v-model="user.phone">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control" name="address" value="{{ Auth::user()->address }}" required autocomplete="address"  v-model="user.address">
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <i class="text-black-50 p-4" style="font-size: 12px">Leave in blank if you want to preserve your current password</i>
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" autocomplete="new-password" v-model="user.password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" v-model="user.password_confirmation">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary btn-lg update">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>

    const profile = new Vue ({
        el: '#profile',
        created: function() {
            console.log('profile');
        },
        data: {
            user: {
                _token  : $('meta[name="csrf-token"]').attr('content'),
                name    : "{{ Auth()->user()->name ?? '' }}",
                lastname: "{{ Auth()->user()->lastname ?? '' }}",
                email   : "{{ Auth()->user()->email ?? '' }}",
                phone   : "{{ Auth()->user()->phone ?? '' }}",
                address : "{{ Auth()->user()->address ?? '' }}",
                photo   : "",
                password: "",
                password_confirmation: '',
            },
        },
        filters: {
        },
        computed:{ 
        },
        methods: {
            updateUser: function() {

                console.log('updating');
                $('.btn.update').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Saving...').attr('disabled', true);
                this.user.phone = this.user.phone.replace(/\D/g,'');

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
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
                        msg  = 'Your user was updated successfully!';
                        icon = 'success';
                        this.user.photo = response.data.data.user.photo; 
                        
                        swal(msg, "", icon);    
                    } else {
                        msg = 'There was an error. Please try again';
                        icon = 'error';
                        swal(msg, "", icon);
                    }
                    
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    
                        
                }).catch( error => {
                    
                    txt = "";
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    $.each( error.response.data.errors, function( key, error ) {
                        txt += error + '\n';
                    });
                    
                    swal('There was an Error', txt, 'error');
                });
            },
            onFileChange(e){
                console.log(e.target.files[0]);
                this.user.photo = e.target.files[0];
            },
        },
    });


    
</script>
@endpush
