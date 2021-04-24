@extends('layouts.app')

@section('content')
<div class="container"  id="create_loveone">

    <form method="POST" action="#" style="width: 100%;" class="row" v-on:submit.prevent="createLoveone()">
        @csrf

        <div class="col-md-12">
            <h3>Tell us about your love one</h3>
        </div>

        <div class="col-md-6 mt-5">

            <div class="form-group row">
                <label for="firstname" class="col-md-4 col-form-label text-md-right">First firstName</label>

                <div class="col-md-6">
                    <input id="firstname" type="text" class="form-control" name="firstname" value="" required autocomplete="firstname" autofocus v-model="loveone.firstname">
                </div>
            </div>
            <div class="form-group row">
                <label for="lastname" class="col-md-4 col-form-label text-md-right">Lastname</label>

                <div class="col-md-6">
                    <input id="lastname" type="text" class="form-control" name="lastname" value="" required autocomplete="lastname" autofocus v-model="loveone.lastname">
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" v-model="loveone.email">
                </div>
            </div>
            
            <div class="form-group row">
                <label for="phone" class="col-md-4 col-form-label text-md-right">Phone Number</label>

                <div class="col-md-6">
                    <input id="phone" type="tel" class="form-control" name="phone" value="" required autocomplete="phone" autofocus v-model="loveone.phone">
                </div>
            </div>

            <div class="form-group row">
                <label for="address" class="col-md-4 col-form-label text-md-right">Address</label>

                <div class="col-md-6">
                    <input id="address" type="text" class="form-control" name="address" value="" required autocomplete="address" autofocus v-model="loveone.address">
                </div>
            </div>

            <div class="form-group row">
                <label for="dob" class="col-md-4 col-form-label text-md-right">Date of Birth</label>

                <div class="col-md-6">
                    <input id="dob" type="date" class="form-control" name="dob" required autocomplete="dob" v-model="loveone.dob">
                </div>
            </div>

            <div class="form-group row">
                <label for="relationship" class="col-md-4 col-form-label text-md-right">Relationship</label>

                <div class="col-md-6">
                    <select name="relationship" id="relationship" v-model="loveone.relationship_id" class="form-control">
                        @foreach ($relationships as $relationship)
                            <option value="{{$relationship->id}}" {{(isset($loveone) && $relationship->id  == $loveone->relationship_id) ? 'selected' : ''}}>{{$relationship->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4 mt-4">
                    <button class="btn btn-primary loadingBtn btn-lg" type="submit" data-loading-text="Loading..." id="saveBtn">
                        Save
                    </button>
                    <input type="hidden" name="id" v-model="loveone.id" value="">
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-5">

            <div class="form-group row">
                <label for="photo" class="col-md-4 col-form-label text-md-right">Photo</label>

                <div class="col-md-6">
                    <input id="photo" type="file" class="form-control" name="photo" value="" autofocus>
                </div>
            </div>

            <p>Conditions</p>

            @foreach ($conditions as $condition)
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="{{$condition->id}}" id="{{Str::slug($condition->name)}}" v-model="loveone.condition_ids">
                    <label class="form-check-label" for="{{Str::slug($condition->name)}}">
                        {{$condition->name}}
                    </label>
                </div>
            @endforeach
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    
</style>
@endpush

@push('scripts')
<script>

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
            }
        }
    });
    
</script>
@endpush
