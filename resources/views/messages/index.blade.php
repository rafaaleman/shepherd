@extends('layouts.app')

@section('content')
<div class="container p-0"  id="messages">
    
    <div class="row mb-3 align-items-center justify-content-center">
        <div class="col-sm-12 col-md-8 col-lg-8 row">
       
        </div>
        <div class="col-4 d-none d-sm-none d-lg-block">
            <a href="javascript:;" @click="showModal()" class="float-right btn  btn-primary btn-lg  rounded-pill text-white">
                Send New Message
            </a>        
        </div>
    </div>

    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 p-0">
        {{-- {{ dump(Auth::user())}} --}}
            <div class="alert alert-info col-8 ml-auto mr-auto text-center" :class="m1 ? 'block': 'd-none'">     <h3>No messages </h3>
            </div>
            <!-- Row start -->
            <div class="row no-gutters">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="users-container">
                        <div class="chat_list" v-for="(chat, i) in chats" :id=" 'chat_' + chat.id " @click="selectChat(chat)" :class="(selected_chat == chat.id
                        ) ? 'active_chat' : '' ">

                            <div class="chat_people">
                                <div class="chat_img"> 

                                    <img class="img-fluid" :src="chat.user.photo" alt="User Photo" v-if="chat.user.photo"> 
                                    <img class="img-fluid" src="{{asset('img/no-avatar.png')}}" alt="User Photo" v-else> 
                                    
                                </div>
                                <div class="chat_ib">
                                    <h5>                                    
                                        @{{ chat.user.name +' '+  chat.user.lastname}} 
                                        <span class="new_msg" :id=" 'newchat_' + chat.id "  v-if="chat.status == 1"></span>
                                        
                                    </h5>
                                    <p>@{{chat.last_message}} </p>
                                </div>
                            </div>
                            <span class="trash fa fa-trash " @click="deleteChat(i,chat)"></span>
                        </div>

                    </div>
                </div>
                <div class="col-xl-8 col-lg-6 col-md-8 col-sm-12">
                    <div class="chat-container" v-if="selected_chat">
                        <ul class="chat-box chatContainerScroll" ref="message_list">

                            <li class="chat" v-for="(message,i) in messages">
                                <div class="chat-avatar" >
                                    <template v-if="message.id_user != user" >
                                        <img class="img-fluid" :src="user_photo"  v-if="user_photo !='' ">
                                        <img class="img-fluid" src="{{asset('img/no-avatar.png')}}" alt="User Photo" v-else> 
                                    </template>
                                    <template v-if="message.id_user == user">
                                        <img class="img-fluid" :src="selected_chat2.user.photo" v-if="selected_chat2.user.photo != ''" >
                                        <img class="img-fluid" src="{{asset('img/no-avatar.png')}}" alt="User Photo" v-else> 
                                    </template>
                                    
                                    
                                    
                                    
                                    
                                </div>
                                <div class="chat-text">
                                    @{{ message.message }}
                                    <span class="trash fa fa-trash " @click="deleteMsg(i,message)"></span>
                                </div>
                                <div class="chat-hour">@{{ message.created_at | formatDate }}</div>
                            </li>                           
               
                        </ul>
                        <div class="form-group mt-3 mb-0">
                            <textarea class="form-control" rows="3" placeholder="Type your message and press Enter..." v-model="message" v-on:keyup.enter="sendMessage"></textarea>
                        </div>
                        <br>
                        <input type="checkbox" id="urgent" name="urgent" v-model="urgent">
                        <label for="urgent">Mark this message as urgent</label>
                        <a class="btn btn-primary btn-sm" @click="sendMessage">Send Message</a>
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
    padding: 1rem;
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
    position: relative;
    box-shadow: 0px 2px 4px #e1e4e7;
    border-radius: 4px;
    margin-bottom: 10px;
}
.active_chat{
  border-bottom: 1px solid #f0f4f8;
  border-right: 5px solid #359cb6;
}

.chat_people{ overflow:hidden; clear:both;}

.chat_img {
  float: left;
  width: 22%;
}

.chat_img img {
    -webkit-border-radius: 50px;
    -moz-border-radius: 50px;
    border-radius: 50px;
    max-width: 60px;
}

.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 78%;
}


.chat_ib h5{ font-size:19px; color:#359cb6; margin:0 0 8px 0;font-weight: bold;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:13px; color:#78849e; margin:auto;font-weight: bold;}


.chat_list:hover .trash{
    display: block;
}
.chat_list .trash{
    display: none;
    position: absolute;
    bottom: 5px;
    right: 5px;
}

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
    background: white;
    padding: 1rem;
    border-radius: 6px;
    border: 1px solid #efefef;
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
    background: #f1f1f1;
    border-radius: 4px;
    font-weight: 300;
    line-height: 150%;
    position: relative;
    width: 90%
}
.chat-container li .chat-text:hover .trash {
    display:block;	
}

.chat-container .trash{
    display: none;
    position: absolute;
    bottom: -6px;
    right: 0px;
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

.chatContainerScroll {
    list-style-type: none;
    margin: 0;
    padding: 30px 10px 0 0;
    overflow-x: hidden;
    overflow-y: auto;
    max-height : 600px;
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
            user_photo : '{{ Auth::user()->photo }}',
            user_send: null,
            selected_chat: null,
            selected_chat2: null,
            contacts: [],
            chats:[],
            messages:[],
            message:null,
            status: false,
            urgent:false,
            m1: false,
            m2: false
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
                    value = value[1].split('.');   

                    return moment(value[0], "HH:mm:ss").format("hh:mm A");
                   // return moment(String(value[0])).format('hh:mm');
                }
            },
            Username : function(value){
                return  this.contacts.find(name => contact.name === value);
            }
            
        },
         computed:{ 
 
         },
         watch:{
            messages(messages){
                this.scrollToBottom();
            }
         },
         methods: 
         {
            scrollToBottom(){
                setTimeout(() => {
                    this.$refs.message_list.scrollTop = this.$refs.message_list.scrollHeight - this.$refs.message_list.clientHeight;
                },50);
            },
            showModal() {
                //this.borrar();
                $('#contactsModal').modal('show');
            },
            borrar(){
                this.user_send= null;
                this.contacts = [];
                this.selected_chat = null; 
                this.messages = [];
                this.chats = [];
                this.status = false;
                this.message = null;
                this.urgent = false;
            },
            getCareteam: function(){
                var url = '{{ route("messages.careteam",$loveone_slug) }}';  
                axios.get(url).then(response => {
                    if(response.data.data.careteam.length > 0){
                        this.m2 = false;
                    }else{
                        this.m2 = true;
                    }
                    this.contacts = response.data.data.careteam;
                });
            },
            getChats: function(){
                var url = '{{ route("messages.chats",$loveone_slug) }}';

                axios.get(url).then(response => {
                    if(response.data.data.chats.length > 0){
                        this.m1 = false;
                    }else{
                         this.m1 = true;
                    }
                    this.chats = response.data.data.chats;  
                });
            },
            getChat: function(){
                var url = '{{ route("messages.chat","*ID*") }}';
                url = url.replace('*ID*', this.selected_chat);                
                axios.get(url).then(response => {
                    this.messages = response.data.data.chat;

                    console.info(this.messages);
                });
            },
            newChat: function(contact){
                let x = this.chats.find(chat => chat.receiver_id === contact.id);
                let y = this.chats.find(chat => chat.sender_id === contact.id);
                if(x){
                    this.selectChat(x);                    
                }else if(y){
                    this.selectChat(y);
                }else{
                    var url = '{{ route("messages.chat.new",$loveone_slug) }}';
                    let msg = {
                        user_id: contact.id
                    };
                    axios.post(url, msg).then(response => {
                        let chat = response.data.data.chat;
                        this.selectChat(chat);
                        this.getChats();                   
                    });   
                }
                $('#contactsModal').modal('hide');
            },
            selectChat: function(chat){
                $("#newchat_"+chat.id).addClass('d-none');
                this.selected_chat = chat.id;
                this.selected_chat2 = chat;
                this.message = null;
                this.urgent = false;
                this.changeUser(chat);
                
                this.getChat();
                Echo.private("chat."+ chat.id ) 
                    .listen('NewMessage',(e)=>{ 
                        console.log(e);                       
                        this.newM(e.message);
                    });
             },
             changeUser: function (chat){
                if(chat.sender_id != this.user){
                    this.user_send = chat.sender_id;
                }else{
                    this.user_send = chat.receiver_id;
                }
             },
             sendMessage: function(e){
                 e.preventDefault();
                 
                if(this.message == '' ){ return;}

                var url = '{{ route("messages.store") }}';
                let msg = {
                        user_id: this.user_send,
                        chat_id: this.selected_chat,
                        message: this.message,
                        urgent:  this.urgent
                    };
                axios.post(url, msg).then(response => {
                    this.message = null;
                    this.messages = response.data.data.chat;                    
                });
             },
             newM: function(M){
                this.messages.push(M);
             },
             deleteMsg(index,message){
                var url = '{{ route("messages.delete","*ID*") }}';                
                url = url.replace('*ID*', message.id);
                axios.get(url).then(response => {                    
                    this.messages.splice(index,1);
                });
             },
             deleteChat(index,chat){                
                var url = '{{ route("messages.chat.delete","*ID*") }}';                
                url = url.replace('*ID*', chat.id);
                axios.get(url).then(response => {                    
                    location.reload();
                });
                
             }
         }
     });

     
 </script>
@endpush