@extends('layouts.app')

@section('content')
<div class="container p-0"  id="discussion-app">

    <div class="row mb-3 align-items-center justify-content-center">
        <div class="col-12 row">
            <div class="col-4 p-0">
                <div id="custom-search-input">
                    <div class="input-group col-md-12">
                        <input type="text" class="form-control input-lg" placeholder="Search" id="txtSearch" v-model="searchText"/>
                        <span class="input-group-btn">
                            <button class="btn btn-info btn-lg" type="button" @click="findMessage()">
                                <i class="fas fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-8 d-lg-block">
                <a id="new-discussion" href="{{route('discussions.create',$loveone_slug)}}" class="float-right btn btn-sm btn-primary rounded-pill text-white">
                    Add New Discussion
                </a>        
            </div>
        </div>
    </div>   

    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 p-0">
        {{-- {{ dump(Auth::user())}} --}}
        <template v-if="discussions.length > 0">
            <div class="row no-gutters">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="card shadow-sm">
                        <div class="card-body" id="discussions">
                            <div id="cread-header">
                                <p id="txtGral">General Discussions
                                    <a href="" id="linkAll" class="">View All</a>
                                </p>

                            </div>
                            <div class="discussion-container">                                
                                <div class="chat_list" v-for="d in discussions" :key="d.id" :class="(selected_chat == d.id) ? 'active_chat' : '' "  @click="getChat(d)" >
                                    <div class="chat_people">
                                        <div class="chat_img">
                                          <img class="chat-avatar rounded-circle" :src="user_photo" alt="User Photo" v-if="user_photo"> 
                                          <img class="chat-avatar rounded-circle" src="{{asset('img/no-avatar.png')}}" alt="User Photo" v-else>
                                          <img class="chat-avatar2 rounded-circle" :src="avatarPhoto(d.users[0])" alt="User Photo" v-if="user_photo"> 
                                        </div>
                                        <div class="chat_ib">
                                            <h5>@{{ d.name }} <span class="chat_date">@{{ d.created_at | formatDate }}</span></h5>
                                            <p>
                                                @{{ d.last_message }}
                                                <span class="new_msg" v-if="d.new_message == 1" :id="'NM-'+d.id"></span>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xl-8 col-lg-6 col-md-8 col-sm-12 pl-2" id="chat-container">
                    <div class="card">
                        <template v-if="!this.onSearch">
                        <div class="card-body" id="chat-selected" v-if="selected_chat != 0">
                            <ul class="chat-box chatContainerScroll" ref="message_list" id="messages-list">
                                <template v-for="(m,pos) in messages">
                                    <li class="chat" v-if="m.id_user == user" :id="'msgChat'+m.id" :key="pos">
                                        <div class="img-discussion" >
                                            <img class="chat-avatar rounded-circle" :src="user_photo" alt="sunil">
                                        </div>
                                        <div class="chat-text">
                                            @{{m.message}}
                                            <span class="trash fa fa-trash d-none"  role="button"></span>
                                        </div>
                                        <div class="chat-hour">@{{ m.created_at | formatTime }}</div>
                                    </li>
                                
                                    <li class="chat" :id="'msgChat'+m.id" :key="pos" v-else>
                                        <div class="chat-hour">@{{ m.created_at | formatTime }}</div>
                                        <div class="chat-text revert">
                                            @{{m.message}}
                                            <span class="trash fa fa-trash d-none" role="button"></span>
                                        </div>
                                        <div class="img-revert">
                                            <img class="chat-avatar rounded-circle" :src="avatarPhoto(m.id_user)" alt="sunil"> 
                                        </div>
                                    </li>
                                </template>
                            </ul>

                            <div class="form-group mt-3 mb-0">
                                <p id="txt-message">YOUR MESSAGE</p>
                                <textarea class="form-control chat-form" rows="4" v-model="message" v-on:keyup.enter="sendMessage"></textarea>
                                <a id="btn-send" class="btn btn-primary btn-sm" @click="sendMessage">submit</a>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="card-body" id="chat-selected">
                            <ul class="chat-box">
                                <template v-for="(m,pos) in searchResult">                                
                                    <li class="chat chat-search" :key="pos" role="button" @click="selectMessage(m)">
                                        <div class="img-discussion" >
                                            <img class="chat-avatar rounded-circle" :src="avatarPhoto(m.id_user)" alt="sunil"> 
                                        </div>
                                        <div class="chat-text">
                                            @{{m.message}}
                                        </div>
                                        <div class="chat-hour">@{{ m.created_at | formatTime }}</div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>
                    </div>
                </div>


            </div>
        </template>
        <template v-else>
            <div class="alert alert-info col-8 ml-auto mr-auto text-center">     
                <h3>No Messages</h3>
            </div>
        </template>
    </div>

</div>

@endsection

@push('styles')
<style>
    #new-discussion{
        font-size: .8rem;
        margin-top: 10px;
    }

    #custom-search-input{
        padding: 3px;
        border: solid 1px #E4E4E4;
        border-radius: 60px;
        background-color: #fff;
    }

    #custom-search-input input{
        font-size: 12px;
        border: 0;
        box-shadow: none; 
    }

    #custom-search-input input:focus{
        outline: none;
        box-shadow: none !important;
        border: 0 !important;
        outline-width: 0 !important;    

    }

    #custom-search-input button{
        font-size: 16px;
        margin: 2px 0 0 0;
        background: none;
        box-shadow: none;
        border: 0;
        color: #666666;
        padding: 0 8px 0 10px;
        border-left: solid 1px #ccc;
        border-radius: 0 !important;
    }

    #custom-search-input button:hover{
        border: 0;
        box-shadow: none;
        border-left: solid 1px #ccc;
    }

    #custom-search-input .fas{
        font-size: 10px;
    }

    #discussions{
        padding: 0;
        clear: both;
        overflow: hidden;
    }

    #cread-header{
        padding: .5rem;
    }

    #txtGral{
        font-family: Gotham;
        font-size: 12px;    
        color: #78849e;
        margin-bottom: 0;
    }

    #linkAll{
        font-size: 12px;
        color: #dea771;
        float: right;
    }

    .discussion-container { min-height: 560px; overflow-y: auto;}

    

    .chat_ib h5{ 
      font-family: Gotham;
      font-size: 15px;
      font-weight: bold;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.2;
      letter-spacing: normal;
      text-align: left;
      color: #454f63;
    }

    .active_chat{ background:#f8fafc;}
    .active_chat .chat_ib h5 {color: #dd7615}
    .active_chat .chat_ib h5 span {color: #454f63;}
    .chat_ib h5 span{ font-size:10px; font-weight: 100; float:right;}

    .chat_ib p{ 
      font-family: Gotham;
      font-size: 12px;
      margin:auto
    }

    .new_msg {
        float: right;
        width: 10px;
        height: 10px;
        -webkit-border-radius: 100px;
        -moz-border-radius: 100px;
        border-radius: 100px;
        background: #e6ecf3;
        top: 0;
        right: 0;
        background:  #dd7615;
    }

   
    .chat_img {
      float: left;
      width: 30px;
      height: 30px;
    }

    .chat-avatar {     
        width: 30px;
        height: 30px;
    }

    .chat-avatar2 { 
        width: 30px;
        height: 30px;
        position: relative;
        left: 12px;
        bottom: 17px;
    }

    .chat_ib {
      float: left;
      padding: 0 0 0 15px;
      width: 88%;
    }

    .chat_people{ overflow:hidden; clear:both;}

    .chat_list {
      border-bottom: 1px solid #c4c4c4;
      margin: 0;
      padding: 10px 10px 10px;
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

    /*********************************************************************************************
                                        Chat right side
    *********************************************************************************************/
    #chat-container{
        background-image: url("{{ asset('img/bg-discussions.svg')}}");
        background-repeat: no-repeat;
        background-position: center;
    }

    #chat-selected{
        min-height: 595px;
        padding: .3rem;
        background-color: #f8fafc;
    }

    #chat-selected .chat-box{
        padding-top: 20px;
        min-height: 410px;
    }


    #chat-selected li.chat {
        display: flex;
        flex: 1;
        flex-direction: row;
        margin-bottom: 40px;
    }

    #chat-selected li .chat-text:hover .trash {
        display:block;	
    }

    #chat-selected .trash{
        display: none;
        position: absolute;
        bottom: 6px;
        right: 6px;
    }

    .img-discussion {
        width: 7%;
        right: -16px;
        position: relative;
        top: -16px;
        z-index: 100000;
    }

    .img-revert {
        width: 7%;
        left: -16px;
        position: relative;
        top: -16px;
        z-index: 100000;
    }

    #chat-selected li .chat-text {
        font-family: Gotham;
        font-size: 12px;
        padding: .4rem 1.5rem;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        background: #ffffff;
        border-radius: 10px;
        font-weight: 300;
        line-height: 150%;
        position: relative;
        width: 90%
    }

    #chat-selected li .revert {
        padding: .5rem 0 0 .5rem;
        background: #369bb6;
        color: #ffffff;
    }

    #chat-selected li .chat-hour {
        padding: 0;
        margin-bottom: 10px;
        font-size: .5rem;
        display: flex;
        flex-direction: row;
        font-weight: bold;
        justify-content: center;
        margin: 0 0 0 15px;
    }

    #txt-message {
      font-family: Gotham;
      font-size: 10px;
      text-align: left;
      color: #78849e;
      margin-bottom: .5rem;
    }

    .chat-form {
        display: inline;
        position: relative;
        width: 60%;
        background-color: #ffffff;
        border-radius: 13px;
    }

    #btn-send{
        margin-bottom: 2.5rem;
        margin-left: 1rem;
    }

    .chatContainerScroll {
        list-style-type: none;
        margin: 0;
        padding: 30px 10px 0 0;
        overflow-x: hidden;
        overflow-y: auto;
        max-height : 450px;
    }
</style>
@endpush

@push('scripts')
<script>
         const messages = new Vue ({        
         el: '#discussion-app',
         created: function () {
            this.selectChat({{$selected_discussions}});
         },
         data: 
         {
            user : {{ Auth::id() }},
            user_photo : '{{ asset(Auth::user()->photo) }}',
            discussions: @json($discussions),
            messages:[],
            careteam :  @json($careteam),
            slug : '{{$loveone_slug}}',
            selected_chat: 0,
            message : "",
            urgent : 0,
            onSearch: false,
            searchText: '',
            searchResult:[],
         },
         filters: {
            mayuscula: function (value) {
                if (!value) return ''
                value = value.toString();
                return value.toUpperCase(); 
            },
            formatTime: function(value) {
                if (value) {     
                    value = value.split('T');   
                    value = value[1].split('.');
                    return moment(value[0], "HH:mm:ss").format("hh:mm A");
                }
            },
            formatDate: function(value) {
                if (value) {     
                    value = value.split('T');   
                    return moment(value[0]).format("MMM DD");
                }
            },
            Username : function(value){
                return  this.careteam.find(user => user.id === value);
            }
        },
         computed:{ 
 
         },
         watch:{
            messages(messages){
                if(messages.lenght >0){
                    this.scrollToBottom();
                }
            }
         },
         methods: 
         {
            scrollToBottom(){
                setTimeout(() => {
                    this.$refs.message_list.scrollTop = this.$refs.message_list.scrollHeight - this.$refs.message_list.clientHeight;
                },50);
            },
            avatarPhoto : function(value){
                const user = this.careteam.find(user => user.id == value);
                
                if(user){
                    return  '..' + user.photo;
                }else{
                    return "https://ptetutorials.com/images/user-profile.png";
                }
            },
            selectChat(c,top = 0){
                this.selected_chat = c;
                if(c != 0){
                    let x =   this.discussions.find(discussion => discussion.id === c);
                    this.getChat(x,top);
                }
            },
            getChat(d,top = 0){
                this.selected_chat = d.id;
                this.urgent = d.urgent;
                $("#NM-"+d.id).addClass('d-none');

                var url = '{{ route("discussions.chat","*ID*") }}';
                url = url.replace('*ID*', this.selected_chat);                
                axios.get(url).then(response => {
                    this.messages = response.data.data.messages;
                    this.onSearch     = false;
                    this.searchText   = '';
                    this.searchResult = [];
                }).then(() =>{
                    if(top != 0){
                        let m = '#msgChat'+top;
                        $('#messages-list').animate({
                            scrollTop: $('#messages-list '+m).position().top
                        }, 'slow');
                    }
                });
                Echo.private("chat."+ d.id ) 
                    .listen('NewMessage',(e)=>{ 
                        this.messages.push(e.message);
                });
            },
            selectMessage(message){
                this.selectChat(message.id_chat,message.id);
            },
            findMessage(){
                let url = '{{ route("discussions.find","*ID*") }}';
                let data = { txt: this.searchText };
                url = url.replace('*ID*', this.slug);
                this.onSearch = true;
                console.log(data);
                axios.post(url,data).then(response => {
                    this.messages =[];
                    this.selected_chat=0;
                    this.searchResult = response.data.messages;
                    console.info(response,this.messages);

                });
            },
            sendMessage: function(e){
                 e.preventDefault();                 
                if(this.message == '' ){ return;}
                var url = '{{ route("messages.store") }}';
                let msg = {
                        user_id: this.user,
                        chat_id: this.selected_chat,
                        message: this.message,
                        urgent:  this.urgent,
                        slug: this.slug  
                    };
                axios.post(url, msg).then(response => {
                    this.message = null;
                    this.messages = response.data.data.chat;                    
                });
             },
             newM: function(M){
                this.messages.push(M);
             },

         }
     });
 </script>
@endpush