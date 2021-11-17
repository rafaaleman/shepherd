@extends('layouts.app_simple')

@section('content')
<div class="container-fluid"  id="create_loveone">

    <form id="create_lovedone_form" method="POST" action="#" style="width: 100%;" class="row" v-on:submit.prevent="createLoveone()" enctype="multipart/form-data">
        @csrf
        <div class="cards col-md-5 mt-5 mx-auto">
            <div data-slide="1" class="card p-4 shadow-sm">
                <h4 class="mb-3">Step 1 of 4: Tell us about your loved one</h4>
                <div class="form-group row">
                    <label for="firstname" class="col-md-4 col-form-label text-md-right">First Name <span class="text-danger">*</span></label>

                    <div class="col-md-7">
                        <input id="firstname" type="text" class="form-control" name="firstname" value="{{(isset($loveone)) ? $loveone->firstname : ''}}" required autocomplete="firstname" autofocus v-model="loveone.firstname">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="lastname" class="col-md-4 col-form-label text-md-right">Last Name <span class="text-danger">*</span></label>

                    <div class="col-md-7">
                        <input id="lastname" type="text" class="form-control" name="lastname" value="{{(isset($loveone)) ? $loveone->lastname : ''}}" required autocomplete="lastname" autofocus v-model="loveone.lastname">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="phone" class="col-md-4 col-form-label text-md-right">Phone # <span class="text-danger">*</span></label>

                    <div class="col-md-7">
                        <input required id="phone" type="text" class="form-control" name="phone" value="{{(isset($loveone)) ? $loveone->phone : ''}}" autocomplete="phone" autofocus v-model="loveone.phone">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="dob" class="col-md-4 col-form-label text-md-right">Date of Birth <span class="text-danger">*</span></label>

                    <div class="col-md-7 birthdate">
                        <input required type="number" pattern="[0-9]+" maxlength="2" min="1" max="12" name="dob_month" id="dob_month" placeholder="MM" value="{{ isset($loveone) ? explode('-', $loveone->dob)[1] : '' }}" v-model="loveone.dob_month" class="">
                        <input required type="number" pattern="[0-9]+" maxlength="2" min="1" max="31" name="dob_day" id="dob_day" placeholder="DD" value="{{ isset($loveone) ? explode('-', $loveone->dob)[2] : '' }}" v-model="loveone.dob_day" class="">
                        <input required type="number" min="1910" max="{{date('Y')}}" pattern="[0-9]+" maxlength="4" name="dob_year" id="dob_year" placeholder="YYYY" value="{{ isset($loveone) ? explode('-', $loveone->dob)[0] : '' }}" v-model="loveone.dob_year" class="">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="relationship" class="col-md-4 col-form-label text-md-right">Relationship <span class="text-danger">*</span></label>

                    <div class="col-md-7">
                        <select required name="relationship" id="relationship" v-model="loveone.relationship_id" class="form-control">
                            @foreach ($relationships as $relationship)
                                <option value="{{$relationship->id}}" {{(isset($loveone) && $relationship->id  == $loveone->careteam->relationship_id) ? 'selected' : ''}}>{{$relationship->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="relationship" class="col-md-4 col-form-label text-md-right"></label>

                    <div class="col-md-7">
                        *Asterisk indicates required fields
                    </div>
                </div>
            </div>

            <div data-slide="2" class="card p-4 shadow-sm" style="display:none;">
                <h4 class="mb-3">Step 2 of 4: What are their current medical issues</h4>
                <p>Select the health conditions relevant to your loved one</p>

                <input type="text" id="condition" placeholder="Start typing condition" class="form-control mb-4 pr-2 mt-3" name="condition" autocomplete="off">

                <div class="conditions">
                @if (isset($loveone))
                    @php
                        $loveone_conditions = explode(',', $loveone->conditions);
                    @endphp
                    @foreach ($loveone_conditions as $condition)
                        <div class="form-check mb-3 ml-3">
                            <input class="form-check-input" type="checkbox" value="{{$condition}}" id="{{Str::slug($condition)}}" v-model="loveone.conditions" checked>
                            <label class="form-check-label" for="{{Str::slug($condition)}}">
                                {{$condition}}
                            </label>
                        </div>
                    @endforeach

                    @foreach ($conditions as $condition)
                        @if (!in_array($condition->name, $loveone_conditions))
                        
                            <div class="form-check mb-3 ml-3">
                                <input class="form-check-input" type="checkbox" value="{{$condition->name}}" id="{{Str::slug($condition->name)}}" v-model="loveone.conditions">
                                <label class="form-check-label" for="{{Str::slug($condition->name)}}">
                                    {{$condition->name}}
                                </label>
                            </div>
                        @endif
                    @endforeach
                @else
                
                    @foreach ($conditions as $condition)
                        <div class="form-check mb-3 ml-3">
                            <input class="form-check-input" type="checkbox" value="{{$condition->name}}" id="{{Str::slug($condition->name)}}" v-model="loveone.conditions">
                            <label class="form-check-label" for="{{Str::slug($condition->name)}}">
                                {{$condition->name}}
                            </label>
                        </div>
                    @endforeach
                @endif
                </div>
            </div>

            <div data-slide="3" class="card p-4 shadow-sm" style="display:none;">
                <h4 class="mb-3">Step 3 of 4: Additional information</h4>
                <p>Everything here is optional, but it can be helpul to your CareTeam to have this information in the platform.</p>
                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>

                    <div class="col-md-7">
                        <input id="email" type="email" class="form-control" name="email" value="{{(isset($loveone)) ? $loveone->email : ''}}" autocomplete="email" v-model="loveone.email">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="street" class="col-md-4 col-form-label text-md-right">Street Address</label>

                    <div class="col-md-7">
                        <input type="text" id="street" class="form-control" name="street" autofocus v-model="loveone.street" value="{{(isset($loveone)) ? $loveone->street : ''}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="city" class="col-md-4 col-form-label text-md-right">City</label>

                    <div class="col-md-7">
                        <input type="text" id="city" class="form-control" name="city" autofocus v-model="loveone.city" value="{{(isset($loveone)) ? $loveone->city : ''}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="state" class="col-md-4 col-form-label text-md-right">State</label>

                    <div class="col-md-7">
                        <input type="text" id="state" class="form-control" name="state" autofocus v-model="loveone.state" value="{{(isset($loveone)) ? $loveone->state : ''}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="zip" class="col-md-4 col-form-label text-md-right">Zip</label>

                    <div class="col-md-7">
                        <input type="number" maxlength="5" id="zip" class="form-control" name="zip" autofocus v-model="loveone.zip" value="{{(isset($loveone)) ? $loveone->zip : ''}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="relationship" class="col-md-4 col-form-label text-md-right"></label>
                </div>
            </div>

            <div data-slide="4" class="card p-4 shadow-sm" style="display:none;">
                <h4 class="mb-3">Final Step: Loved One Photo</h4>
                <p>Upload a photo of your loved one, it will be used as their user photo.</p>

                <div class="col-md-6 photo-container bg-primary d-flex align-items-center mx-auto pt-2" style="background-image: url('{{(isset($loveone)) ? $loveone->photo : ''}}')">
                    <div class="bigBtn">
                        <i class="far fa-user mb-1" style="font-size: 100px"></i> <br>
                        Click here to upload a photo of your loved one
                    </div>
                    <input id="photo" type="file" class="form-control d-none" name="photo"  v-on:change="onFileChange" accept=".jpg, .png">
                </div>
            </div>

            <div class="form-group row mb-0 mx-auto">
                <div class="col-md-6 mt-4 mx-auto text-center">
                    <button class="d-none btn btn-primary loadingBtn btn-lg mb-4" type="submit" data-loading-text="Loading..." id="saveBtn">
                        Save Loved One
                    </button>
                    <input type="hidden" name="id" v-model="loveone.id" value="">
                </div>
            </div>
        </div>
    </form>
    <div class="form-group row mb-0">
        <div class="col-md-6 mt-4 mx-auto text-center">
            <button class="btn btn-primary loadingBtn btn-lg mb-4 d-none" id="prevBtn">
                Previous Step
            </button>
            <button class="btn btn-primary loadingBtn btn-lg mb-4" id="nextBtn">
                Next Step
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .error { color: red; }

    #dob_day-error,
    #dob_month-error,
    #dob_year-error {
        position: absolute;
        bottom: -25px;
    }
    
    .birthdate {
        display: flex;
        padding: 0 15px;
        justify-content: space-between;
    }

    .birthdate input {
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
    border-radius: 0.25rem;
    }

    .birthdate #dob_month,
    .birthdate #dob_day{
        width: 22%;
    }
    
    .birthdate #dob_year{
        width: 47%;
    }

    .form-group{
        margin-bottom: 10px;
    }
    .top-bar{
        display: none !important;
    }

    .top-navigation{
        margin-bottom: 0 !important;
    }

    .photo-container{
        height: 93vh;
    }

    #moreResults{
        display: none !important;
    }

    /******************************* MOBILE *********************************/
    @media (max-width: 576px) {
        .photo-container{
            height: 240px;
        }

        form.row{
            margin-left: 0;
            margin-right: 0;
            margin-top: 20px;
        }
    }
</style>
<link href='https://clinicaltables.nlm.nih.gov/autocomplete-lhc-versions/17.0.2/autocomplete-lhc.min.css' rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js" integrity="sha512-37T7leoNS06R80c8Ulq7cdCDU5MNQBwlYoy1TX/WUsLFC2eYNqtKlV0QjH7r8JpG/S0GUMZwebnVFLPd6SU5yg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
<script src='https://clinicaltables.nlm.nih.gov/autocomplete-lhc-versions/17.0.2/autocomplete-lhc.min.js'></script>
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

    //Image on change
    $('#photo').on('change', function(e) {
        e.preventDefault();

        var file = $(this);
        var size = file[0].files[0].size;
		var ext = $(this).val().split('.').pop().toLowerCase();
        //Check Size
        if ((size/1024) < 30720) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.photo-container').css('background-image', 'url('+e.target.result+')');
                $('.photo-container .bigBtn').css('opacity', 0.3);
            }

            reader.readAsDataURL(file[0].files[0]);
        } else {
            //Show Error
            swal('Error', 'The maximun file size is 30MB.', 'error')
        }
        
        return false;
    });

    $('#condition').on("keypress", function(e) {
        if (e.keyCode == 13) {
            e.stopPropagation();
            return false; // prevent the button click from happening
        }
    });

    // Registration stepper
    let currSlide = 1;
    let prevBtn = $('#prevBtn');
    let nextBtn = $('#nextBtn');
    let lastSlide = 3;
    let form = $('#create_lovedone_form');
    let validator = $( "#create_lovedone_form" ).validate();


    nextBtn.on('click', function(){

        function next(){
            $('#prevBtn').removeClass('d-none');
            $(".cards").find('[data-slide="'+currSlide+'"]').fadeToggle('fast', function () {
                $(".cards").find('[data-slide="'+ (currSlide+1) +'"]').fadeToggle();
                currSlide = currSlide + 1;
            });
            
        }

        if(currSlide > 1){
            next();
        }

        if( currSlide === 1){
            if(validator.element( "input#firstname" )
                && validator.element( "input#lastname" )
                && validator.element( "input#phone" )
                && validator.element( "input#dob_month" )
                && validator.element( "input#dob_day" )
                && validator.element( "input#dob_year" )
                && validator.element( "select#relationship" ))
            {    
                next()
            }
        }

        console.log(currSlide);
        
        if( currSlide >= lastSlide){
            $('#nextBtn').addClass('d-none');
            $('#prevBtn').addClass('d-none');
            $('#saveBtn').removeClass('d-none');
        }
    })
    
    prevBtn.on('click', function(){
        if(currSlide > 0)
            $(".cards").find('[data-slide="'+currSlide+'"]').fadeToggle('fast', function(){
                $(".cards").find('[data-slide="'+ (currSlide-1) +'"]').fadeToggle();
                currSlide = currSlide - 1;
            });
        
        if( currSlide === 1)
            $('#prevBtn').addClass('d-none');

        if(currSlide <= lastSlide)
            $('#nextBtn').removeClass('d-none');
    })

    // Conditions autocomplete
    var opts = {
        matchListValue: true,
    };
    new Def.Autocompleter.Search('condition', 'https://clinicaltables.nlm.nih.gov/api/conditions/v3/search', opts);
    Def.Autocompleter.Event.observeListSelections('condition', function(data) {
        console.log(data.used_list);
        if(data.used_list){
            condition = data.final_val;
            condition_html = '<div class="form-check mb-3 ml-3">'+
                                '<input class="form-check-input" type="checkbox" value="'+condition+'" id="'+condition+'" v-model="loveone.conditions" checked>'+
                                '<label class="form-check-label" for="'+condition+'">'+condition +'</label>'+
                            '</div>';
            $('.conditions').prepend(condition_html);
            $('#condition').val('');
            create_loveone.loveone.conditions.push(condition);
        }
    });
})

@php
if(isset($loveone) && $loveone->conditions != ''){
    $loveone_conditions = explode(',', $loveone->conditions);
} else {
    $loveone_conditions = [];
}
@endphp

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
                street:"{{ $loveone->street ?? '' }}",
                city:"{{ $loveone->city ?? '' }}",
                state:"{{ $loveone->state ?? '' }}",
                zip:"{{ $loveone->zip ?? '' }}",
                dob:"{{ $loveone->dob ?? '' }}",
                dob_day:"{{ (isset($loveone)) ? explode('-', $loveone->dob)[2] : '' }}",
                dob_month:"{{ (isset($loveone)) ? explode('-', $loveone->dob)[1] : '' }}",
                dob_year:"{{ (isset($loveone)) ? explode('-', $loveone->dob)[0] : '' }}",
                status:1,
                relationship_id:"{{ $loveone->careteam->relationship_id ?? '' }}",
                conditions: [@php foreach($loveone_conditions as $condition){ echo "'".$condition."',";} @endphp],
                photo:"",
            }
        },
        filters: {
        },
        computed:{ 
        },
        methods: {
            createLoveone: function() {
                console.log('creating');
                this.loveone.phone = this.loveone.phone.replace(/\D/g,'');
                $('.loadingBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.loadingBtn').data('loading-text')).attr('disabled', true);
                this.loveone.phone = this.loveone.phone.replace(/\D/g,'');
                this.loveone.dob = this.loveone.dob_year+'-'+this.loveone.dob_month+'-'+this.loveone.dob_day;

                delete this.loveone.dob_day;
                delete this.loveone.dob_month;
                delete this.loveone.dob_year;

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
                        swal('There was an error.', response.data.msg , 'error');
                    }
                    
                    $('.loadingBtn').html('Save').attr('disabled', false);
                    
                        
                }).catch( error => {
                    console.log(response.data);
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
