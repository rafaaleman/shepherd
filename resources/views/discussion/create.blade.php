@extends('layouts.app')

@section('content')
<div class="container p-0"  id="discussion-app">
    
    <div class="col-12 mb-2">
        <h5 id="header-user-list">Select User:</h5>
        <div class="avatar-group justify-content-start">

            <div class="user " role="button" v-for="(c,i) in careteam"  :key="c.id" v-bind:class="c.selected == true ? 'selected' : 'deselected'" @click="getUsers(i)" >
                <div class="avatar avatar-xl border border-3 border-light rounded-circle">
                    <img class="rounded-circle" src="https://ptetutorials.com/images/user-profile.png" alt="">
                </div>
                <p class="user-name">@{{ c.name}} <br/> @{{ c.lastname }}</p>
            </div>
        </div>
    </div>   

    <div class="col-12">
        <form method="POST" action="#" class="row mx-0 fs-0" style="width: 100%;" v-on:submit.prevent="create()">
            <div class="col-9 pl-0">
                <div class="card shadow-sm">
                    <div class="card-body row">
                        <input id="name" type="text" name="name" v-model="discussion.name" placeholder="Discussion Name" required="required" class="form-control only-border-bottom">
                    </div> 
                </div>
            </div>
            
            <div class="col-2 ">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="urgent" id="urgent" v-model="discussion.urgent"/>
                    <label class="form-check-label" for="urgent">Set as <p class="font-weight-bold">Urgent</p></label>
                  </div>
            </div>
            
            <div class="col-12 mt-3">
                <span id="your-message">Your Message</span>
            </div>
            <div class="card shadow-sm my-2 col-12">
                <div class="card-body row">
                    <textarea id="message" rows="7" name="message" maxlength="500" class="form-control no-border no-focus" v-model="discussion.message" required="required"></textarea>
                </div>
            </div> 
            
            
                <div class="col-md-12 mt-4 mb-4 justify-content-center">
                    <center>
                        <button type="submit" data-loading-text="Saving..." id="saveBtn" class="btn btn-primary loadingBtn btn-lg">
                           Add
                        </button>
                    </center> 
                
            </div>

        </form>
    </div>

</div>

@endsection

@push('styles')
<style>
    #header-user-list{
        font-size: 1.2rem;
        font-weight: 700;
    }

    .avatar-group {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: nowrap;
        flex-wrap: nowrap;
    }

    .justify-content-end {
        -webkit-box-pack: end !important;
        -ms-flex-pack: end !important;
        justify-content: flex-end !important;
    }

    .justify-content-start {
        -webkit-box-pack: start !important;
        -ms-flex-pack: start !important;
        justify-content: flex-start !important;
    }

    .avatar-group .user {
        margin-right: .5rem;
    }
    
    .avatar-group .user.deselected:hover {
        filter: opacity(1);
    }
    
    .avatar-group .user.deselected{
        filter: opacity(0.5);
    }
    
    .avatar-group .user.selected{
        filter: opacity(1);
    }
    
    .avatar-group .user.selected:hover{
        filter: opacity(.5);
    }

    .avatar-group .user.selected .avatar{
        box-shadow: 1px 1px 7px 5px #61b5f9;
    }    

    .avatar-group .avatar {
        vertical-align: top;
    }

    .avatar-xl {
        height: 3.5rem;
        width: 3.5rem;
    }
    
    .avatar {
        position: relative;
        display: inline-block;
    }
    
    .avatar img {
        -o-object-fit: cover;
        object-fit: cover;
        width: 100%;
        height: 100%;
        display: block;
    }

    .user-name{
        font-family: Gotham;
        font-size: 12px;
        text-align: center;
        color: #454f63;
        margin-top: .3rem;
    }

    #your-message{
        font-family: Gotham;
        font-size: 14px;
        text-align: center;
        color: #454f63;
        margin-top: .3rem;
    }

</style>
@endpush

@push('scripts')
<script>
         const messages = new Vue ({        
         el: '#discussion-app',
         created: function() 
         {

         },
         data: 
         {
            user : {{ Auth::id() }},
            user_photo : '{{ Auth::user()->photo }}',
            discussion:{
                        'id'      : 0,
                        'owner'   : {{ Auth::id() }},
                        'name'    : '',
                        'urgent'  : false,
                        'message' : '',
                        'users'   : []
                        },
            careteam :  @json($careteam),
            slug : '{{$loveone_slug}}'
         },
         filters: {
            mayuscula: function (value) {
                if (!value) return ''
                value = value.toString();
                return value.toUpperCase(); 
            }           
        },
         methods: 
         {
            getUsers(i){
                if(i == 'a') return (this.discussion.users.length < 1)?false:true; 
                this.careteam[i].selected = !(this.careteam[i].selected);
                let filtered = this.discussion.users.filter(function(element){                    
                    return element == messages.careteam[i].id;
                });

                if(filtered.length < 1){
                    this.discussion.users.push(this.careteam[i].id);
                }else{
                    this.discussion.users.pop(this.careteam[i].id);
                }
                
            },
            create(){
                var formData = new FormData();

                formData.append('owner',  this.discussion.owner);
                formData.append('name',   this.discussion.name);
                formData.append('urgent', this.discussion.urgent);
                formData.append('message',this.discussion.message);
                formData.append('users',  this.discussion.users);
                formData.append('loveone_slug',  this.slug);
                
                if(this.getUsers('a')){
                    var url = "{{route('discussions.store')}}";

                    axios.post(url, formData).then(response => {
                        console.log(response);
                        if(response.data.success == true){
                            let discussion_id =response.data.data.discussion.id;
                            window.location = `../selected/{{$loveone_slug}}/${discussion_id}`;
                        }
                    }).catch(error => {
                            console.log(error);
                    });
                }else{
                    swal({
                                title: "",
                                text: "You must select one or more users!",
                                icon: "error",
                                buttons: [
                                    "OK!"
                                ],
                            });
                }
            }

         }
     });
 </script>
@endpush