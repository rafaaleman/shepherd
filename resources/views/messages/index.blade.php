@extends('layouts.app')

@section('content')
<div class="container p-0"  id="messages">
    
    <div class="row mb-3 align-items-center justify-content-center">
        <div class="col-sm-12 col-md-8 col-lg-8 row">
       
        </div>
        <div class="col-4 d-none d-sm-none d-lg-block">
            <a href="javascript:;" @click="showModal()" class="float-right btn  btn-primary btn-lg  rounded-pill text-white">
                Add New Message
            </a>        
        </div>
    </div>

    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 p-0">
        {{-- {{ dump(Auth::user())}} --}}
            <!-- Row start -->
            <div class="row no-gutters">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-3 col-3">
                    <div class="users-container">
                        
                        <div class="chat_list" v-for="chat in chats">
                            <div class="chat_people">
                                <div class="chat_img"> 
                                    <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="sunil"> 
                                </div>
                                <div class="chat_ib">
                                    <h5>                                    
                                        @{{ chat.user.name +' '+  chat.user.lastname}} <span class="new_msg" v-if="chat.status == 1"></span>
                                    </h5>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-9 col-9">
                    <div class="chat-container">
                        <ul class="chat-box chatContainerScroll">

                            <li class="chat">
                                <div class="chat-avatar">
                                    <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                    
                                </div>
                                <div class="chat-text">
                                    Etiam in neque ac leo faucibus aliquam a in massa. Pellentesque vestibulum semper justo, a scelerisque lacus efficitur sit amet. Aenean hendrerit quis leo ac lacinia.
                                </div>
                                <div class="chat-hour">08:55<br> AM</div>
                            </li>
                            
                            <li class="chat">
                                <div class="chat-avatar">
                                    <img src="https://www.bootdey.com/img/Content/avatar/avatar3.png" alt="Retail Admin">
                                    
                                </div>
                                <div class="chat-text">
                                    Vestibulum lobortis in massa ac luctus. Nulla sit amet erat sit amet magna eleifend eleifend.
                                </div>
                                <div class="chat-hour">08:56<br> AM</div>
                                
                            </li>
               
                        </ul>
                        <div class="form-group mt-3 mb-0">
                            <textarea class="form-control" rows="3" placeholder="Type your message here..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row end -->

    </div>
    @include('messages.contacts_modal')
</div>

@endsection

@push('styles')
<style>
body{margin-top:20px;}
.users-container {
    position: relative;
    padding: 1rem 0;
    border-right: 1px solid #e6ecf3;
    height: 100%;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
}

.chat_list {
  width: 100%;
  margin: 0;
  padding: 18px 16px 10px;
  cursor: pointer;
  background: #ffffff;
  border-bottom: 1px solid #f0f4f8;
}
.active_chat{
  background: transparent;
  border-bottom: 1px solid #f0f4f8;
}

.chat_people{ overflow:hidden; clear:both;}

.chat_img {
  float: left;
  width: 22%;
}

.chat_img img {
    width: 65px;
    height: 65px;
    -webkit-border-radius: 50px;
    -moz-border-radius: 50px;
    border-radius: 50px;
}

.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 78%;
}

.chat_ib h5{ font-size:19px; color:#32a4ea; margin:0 0 8px 0;font-weight: bold;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:13px; color:#78849e; margin:auto;font-weight: bold;}

.new_msg {
    width: 10px;
    height: 10px;
    -webkit-border-radius: 100px;
    -moz-border-radius: 100px;
    border-radius: 100px;
    background: #e6ecf3;
    top: 0;
    right: 0;
    background: #32a4ea;
}

.chat_list:hover {
    background-color: #ffffff;
    /* Fallback Color */
    background-image: -webkit-gradient(linear, left top, left bottom, from(#e9eff5), to(#ffffff));
    /* Saf4+, Chrome */
    background-image: -webkit-linear-gradient(right, #e9eff5, #ffffff);
    /* Chrome 10+, Saf5.1+, iOS 5+ */
    background-image: -moz-linear-gradient(right, #e9eff5, #ffffff);
    /* FF3.6 */
    background-image: -ms-linear-gradient(right, #e9eff5, #ffffff);
    /* IE10 */
    background-image: -o-linear-gradient(right, #e9eff5, #ffffff);
    /* Opera 11.10+ */
    background-image: linear-gradient(right, #e9eff5, #ffffff);
}


@media (max-width: 767px) {
    .users .person .user img {
        width: 30px;
        height: 30px;
    }
    .users .person p.name-time {
        display: none;
    }
    .users .person p.name-time .time {
        display: none;
    }
}


/*********************************************************************************************
									Chat right side
*********************************************************************************************/
.chat-container {
    position: relative;
    padding: 1rem;
}

.chat-container li.chat {
    display: flex;
    flex: 1;
    flex-direction: row;
    margin-bottom: 40px;
}

.chat-avatar img {
    width: 25px;
    height: 25px;
    -webkit-border-radius: 30px;
    -moz-border-radius: 30px;
    border-radius: 30px;
    position: relative;
    top:-18px;
    left: 10px;
    z-index: 1;
}


.chat-container li .chat-text {
    padding: .4rem 1rem;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    background: #ffffff;
    border-radius: 4px;
    font-weight: 300;
    line-height: 150%;
    position: relative;
    width: 90%
}

.chat-container li .chat-hour {
    padding: 0;
    margin-bottom: 10px;
    font-size: .5rem;
    display: flex;
    flex-direction: row;
    font-weight: bold;
    justify-content: center;
    margin: 0 0 0 15px;
}


@media (max-width: 767px) {
    .chat-container li.chat-left,
    .chat-container li.chat-right {
        flex-direction: column;
        margin-bottom: 30px;
    }
    .chat-container li img {
        width: 32px;
        height: 32px;
    }
    .chat-container li.chat-left .chat-avatar {
        margin: 0 0 5px 0;
        display: flex;
        align-items: center;
    }
    .chat-container li.chat-left .chat-hour {
        justify-content: flex-end;
    }
    .chat-container li.chat-left .chat-name {
        margin-left: 5px;
    }
    .chat-container li.chat-right .chat-avatar {
        order: -1;
        margin: 0 0 5px 0;
        align-items: center;
        display: flex;
        justify-content: right;
        flex-direction: row-reverse;
    }
    .chat-container li.chat-right .chat-hour {
        justify-content: flex-start;
        order: 2;
    }
    .chat-container li.chat-right .chat-name {
        margin-right: 5px;
    }
    .chat-container li .chat-text {
        font-size: .8rem;
    }
}

.chat-form {
    padding: 15px;
    width: 100%;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ffffff;
    border-top: 1px solid white;
}

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

</style>
@endpush

@push('scripts')
<script>
    const messages = new Vue ({        
         el: '#messages',
         created: function() 
         {
             this.getCareteam();
             this.getChats();
         },
         data: 
         {
            user : {{ Auth::id() }},
            selected_user: null,
            contacts: [],
            chats:[],
            messages:[],
            status: false,
         },
         filters: {
            mayuscula: function (value) {
                if (!value) return ''
                value = value.toString();
                return value.toUpperCase(); 
            },
            formatDate: function(value) {
                if (value) {
                    value = value.split('T');
                    
                    return moment(String(value[0])).format('MMM Do YYYY hh:mm');
                }
            },
            Username : function(value){
                return  this.contacts.find(name => contact.name === value);
            }
            
        },
         computed:{ 
 
         },
         methods: 
         {
            showModal() {
                this.borrar();
                $('#contactsModal').modal('show');
            },
            borrar(){
                this.contacts = [];
                this.selected_user = 0;
                this.messages = [];
                this.status = false;
            },
            getCareteam: function(){
                var url = '{{ route("messages.careteam",$loveone_slug) }}';  
                axios.get(url).then(response => {
                    this.contacts = response.data.data.careteam;                    
                });
            },
            getChats: function(){
                var url = '{{ route("messages.chats",$loveone_slug) }}';
                
                axios.get(url).then(response => {
                    this.chats = response.data.data.chats;  
                    
                });
            },
            getDocuments: function() {
                 var url = '{{ route("lockbox",$loveone_slug) }}';
                 axios.get(url).then(response => {
                     this.types = response.data.types;
                     this.documents = response.data.documents;  
                     this.lastDocuments = response.data.lastDocuments;  
                     
                     console.log(response);
                 }).then( data => {
                     this.creaSlide();
                 });
             },
             
         }
     });
 </script>
@endpush