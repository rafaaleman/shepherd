@extends('layouts.app_simple')

@section('content')
<div class="container-fluid"  id="create_loveone">

    <form method="POST" action="#" style="width: 100%;" class="row" v-on:submit.prevent="createLoveone()" enctype="multipart/form-data">
        @csrf

        <div class="col-md-4 photo-container bg-primary d-flex align-items-center">
            <div class="bigBtn">
                <i class="far fa-user mb-1" style="font-size: 100px"></i> <br>
                Upload Photo
            </div>
            <input id="photo" type="file" class="form-control d-none" name="photo"  v-on:change="onFileChange" accept=".jpg, .png">
        </div>

        <div class="col-md-4 mt-5">

            <h4 class="mb-3">Tell us about your love one</h4>

            <div class="card p-4 shadow-sm">
                <div class="form-group row">
                    <label for="firstname" class="col-md-4 col-form-label text-md-right">First Name</label>

                    <div class="col-md-7">
                        <input id="firstname" type="text" class="form-control" name="firstname" value="" required autocomplete="firstname" autofocus v-model="loveone.firstname">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="lastname" class="col-md-4 col-form-label text-md-right">Lastname</label>

                    <div class="col-md-7">
                        <input id="lastname" type="text" class="form-control" name="lastname" value="" required autocomplete="lastname" autofocus v-model="loveone.lastname">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>

                    <div class="col-md-7">
                        <input id="email" type="email" class="form-control" name="email" value="" autocomplete="email" v-model="loveone.email">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="phone" class="col-md-4 col-form-label text-md-right">Phone #</label>

                    <div class="col-md-7">
                        <input id="phone" type="tel" class="form-control" name="phone" value="" required autocomplete="phone" autofocus v-model="loveone.phone">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>

                    <div class="col-md-7">
                        <input id="address" type="text" class="form-control" name="address" value="" required autocomplete="address" autofocus v-model="loveone.address">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="dob" class="col-md-4 col-form-label text-md-right">Date of Birth</label>

                    <div class="col-md-7">
                        <input id="dob" type="date" class="form-control" name="dob" required autocomplete="dob" v-model="loveone.dob">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="relationship" class="col-md-4 col-form-label text-md-right">Relationship</label>

                    <div class="col-md-7">
                        <select name="relationship" id="relationship" v-model="loveone.relationship_id" class="form-control">
                            @foreach ($relationships as $relationship)
                                <option value="{{$relationship->id}}" {{(isset($loveone) && $relationship->id  == $loveone->relationship_id) ? 'selected' : ''}}>{{$relationship->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-4 mt-5">

            <h4 class="mb-3">Conditions</h4>

            <div class="card p-4 shadow-sm">

                @foreach ($conditions as $condition)
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="{{$condition->id}}" id="{{Str::slug($condition->name)}}" v-model="loveone.condition_ids">
                        <label class="form-check-label" for="{{Str::slug($condition->name)}}">
                            {{$condition->name}}
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 mt-4">
                    <button class="btn btn-primary loadingBtn btn-lg" type="submit" data-loading-text="Loading..." id="saveBtn">
                        Save
                    </button>
                    <input type="hidden" name="id" v-model="loveone.id" value="">
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .top-bar{
        display: none !important;
    }

    .top-navigation{
        margin-bottom: 0 !important;
    }

    .photo-container{
        height: 93vh;
    }

    /******************************* MOBILE *********************************/
    @media (max-width: 576px) {
        .photo-container{
            height: 180px;
        }

        form.row{
            margin-left: 0;
            margin-right: 0;
            margin-top: 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>

$(function(){

    $('.bigBtn').click(function(){
        $(' #photo').click();
    });

    //Image on change
    $('#photo').on('change', function(e) {
        e.preventDefault();

        var file = $(this);
        var size = file[0].files[0].size;
		var ext = $(this).val().split('.').pop().toLowerCase();
        //Check Size
        if ((size/1024) < 1024) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.photo-container').css('background-image', 'url('+e.target.result+')');
                $('.photo-container .bigBtn').css('opacity', 0.3);
            }

            reader.readAsDataURL(file[0].files[0]);
        } else {
            //Show Error
            swal('Error', 'The maximun file size is 1MB.', 'error')
        }
        
        return false;
    });
})

    const create_loveone = new Vue ({
        el: '#create_loveone',
        created: function() {
            console.log('create_loveone');
        },
        data: {
            loveone: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: "{{ $loveone->id ?? 0 }}",
                firstname: "{{ $loveone->firstname ?? '' }}",
                lastname:"{{ $loveone->lastname ?? '' }}",
                email:"{{ $loveone->email ?? '' }}",
                phone:"{{ $loveone->phone ?? '' }}",
                address:"{{ $loveone->address ?? '' }}",
                dob:"{{ $loveone->dob ?? '' }}",
                status:1,
                relationship_id:"{{ $loveone->relationship_id ?? '' }}",
                condition_ids: [{{ $loveone->condition_ids ?? '' }}],
                photo:"{{ $loveone->photo ?? '' }}",
            }
        },
        filters: {
        },
        computed:{ 
        },
        methods: {
            createLoveone: function() {
                console.log('creating');
                $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                this.loveone.phone = this.loveone.phone.replace(/\D/g,'');

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
                let formData = new FormData();
                if(this.loveone.photo)
                    formData.append('file', this.loveone.photo);
                
                var loveone = JSON.stringify(this.loveone);
                console.log(loveone);
                formData.append('loveone', loveone);

                var url = '{{ route("loveone.create") }}';
                axios.post(url, formData, config)
                .then(response => {
                    console.log(response.data);
                    
                    if(response.data.success){
                        msg  = 'Your Loveone was saved successfully!';
                        icon = 'success';
                        this.loveone.id = response.data.data.loveone.id; 
                        this.loveone.photo = response.data.data.loveone.photo; 
                        
                        swal(msg, "", icon)
                        .then((value) => {
                            window.location = '{{ route("home") }}';
                        });
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
                this.loveone.photo = e.target.files[0];
            },
        }
    });
    
</script>
@endpush
