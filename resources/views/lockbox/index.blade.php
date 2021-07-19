@extends('layouts.app')

@section('content')
<div class="container"  id="lockbox">
    <div class="row justify-content-center">
        <div class="col-12">
            <h4>RECENT DOCUMENTS</h4>            
                <div class="carrusel">
                    <div v-for="doc in lastDocuments" v-on:click="showM(doc.id,doc)" :class="docVisible(doc,'r')">
                        <img :src="doc.file|isImage" class="carrusel-doc">
                    </div>                    
                </div>            
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <h4>ESSENTIAL DOCUMENTS</h4>
        </div>
                <div v-for="doc in types" v-if="doc.required == 1" v-on:click="showM(doc.id,doc)" :class="doc.asFile ? 'si' : 'no' " class="card document-card col-sm-12 col-md-5 col-lg-5 mr-4  align-middle"  :class="docVisible(doc,'r')" >
                    <div class="card-body">
                        <h5 class="card-title t1">@{{ doc.name }}</h5>
                        <p class="card-text t2">@{{ doc.description}}</p>
                    </div>
                </div>
        
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <h4>ALL DOCUMENTS</h4>
        </div>
        <div class="card document-card col-sm-12 col-md-5 col-lg-5 mr-4 align-middle"  v-for="doc in documents" v-on:click="viewDocument(doc)" :class="docVisible(doc,'r')">
            <div class="card-body">
                <h5 class="card-title t1">@{{ doc.name }}</h5>
                
                <div class="dropdown" :class="docVisible(doc,'d')">
                    <i class="fa fa-ellipsis-v float-right mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button"></i>                
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" v-on:click="deleteDocument(doc)" >Delete</a>
                </div>
                </div>
                <span class="card-text t2">@{{ doc.created_at | formatDate }}</span>
            </div>
        </div>  
        
        <div class="col-12 mt-4 text-center">
            <a href="#!" class="btn btn-primary btn-submit"  v-on:click="showM(4,null)">Add New Document</a>
        </div>        
    </div>
     @include('lockbox.create_modal')
     @include('lockbox.edit_modal')
    
</div>

@endsection

@push('styles')
<style>
.flex {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto
}

.slick-prev, .slick-next {
    z-index: 10;
}

.document-card{
    margin-bottom: .5rem;
    cursor: pointer;
}
.document-card .card-body{
    padding: 10px 0px 10px 50px;
}

.document-card.si{
    font-size: 16px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.19;
    letter-spacing: normal;
    text-align: left;
    color: #369bb6;
}

.document-card.no{
    font-size: 16px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.19;
    letter-spacing: normal;
    text-align: left;
    color: #d36582;
}

.document-card::before {
    font-family: "Font Awesome 5 Free";
    font-weight: 100;
    content: "\f15c";
    display: inline-block;
    padding: .25em .4em;
    font-size: 1.5rem;
    position: absolute;
    top: 10%;
    left: 2%;
}


.document-card.si::after {
    font-family: "Font Awesome 5 Free";
    font-weight: 100;
    content: "\f058";
    display: inline-block;
    padding: .25em .4em;
    font-size: 1.5REM;
    position: absolute;
    top: 10%;
    right: 2%;
}

.document-card.no::after {
    font-family: "Font Awesome 5 Free";
    font-weight: 100;
    content: "\f111";
    display: inline-block;
    padding: .25em .4em;
    font-size: 1.5REM;
    position: absolute;
    top: 10%;
    right: 2%;
}
.t1{
    margin-bottom: 0.01rem;
    font-weight: bold;
}
.t2{
    /* font-family: Gotham; */
    font-size: 12px;  
}

.btn-submit{    
  color: #FFFFFF;

  padding: 12px 21px;
  border-radius: 24px;
  background-color: #369bb6;
}
.carrusel-doc{
    margin: 5px;
    padding: 10px;
    width: 100%;
    max-height: 175px;
    cursor: pointer;
}



fieldset {
    display: none
}

fieldset.show {
    display: block
}

select:focus,
input:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #2196F3 !important;
    outline-width: 0 !important;
    font-weight: 400
}

button:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    outline-width: 0
}

.tabs {
    margin: 2px 5px 0px 5px;
    padding-bottom: 10px;
    cursor: pointer
}

.tabs:hover,
.tabs.active {
    border-bottom: 1px solid #2196F3
}

a:hover {
    text-decoration: none;
    color: #1565C0
}

.box {
    margin-bottom: 10px;
    border-radius: 5px;
    padding: 10px
}

.modal-backdrop {
    background-color: #64B5F6
}

.line {
    background-color: #CFD8DC;
    height: 1px;
    width: 100%
}

@media screen and (max-width: 768px) {
    .tabs h6 {
        font-size: 12px
    }
}
</style>
@endpush


@push('scripts')
<script>


   const lockbox = new Vue ({        
        el: '#lockbox',
        created: function() {
            this.getDocuments();
        },
        data: {
            auth_user: {{Auth::Id()}},
            auth_role: 0,
            documents: [],
            lastDocuments: [],
            types:[],
            careteam:[],
            create_type: false,
            permissions : [{ 'user' : 0, 'r' : 0 , 'u': 0, 'd': 0}],
            docPermissions : [{ 'user' : 0, 'r' : 0 , 'u': 0, 'd': 0}],

            newDocument: {
                user_id: {{Auth::Id()}},
                lockbox_types_id: '',
                loveones_id: {{ $loveone->id }},
                name: '',
                description:'',
                file:'',                
                status: 1,
                permissions : []
            },
            fillDocument: {
                id: '',
                user_id: {{Auth::Id()}},
                lockbox_types_id: '',
                loveones_id: {{ $loveone->id }},
                name: '',
                description:'',
                file:'',                
                status: '',
                permissions : []
            },
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
            isImage(file){
                let exts = ['jpg','jpeg','gif','png','svg'];
                let str = "{{asset('images/no_photo.jpg')}}";
                let ext = "txt";
                
                if(file){
                if(file.name){
                    ext = file.name.split('.').pop();
                }else{
                    ext = file.split('.').pop();
                }
                if(exts.indexOf(ext) >= 0){
                        str = "{{ URL::to('/') }}" + file;
                }
                else if(ext == "pdf"){
                        str = "{{asset('images/file_pdf.jpg')}}";
                }
                else if(ext == "doc" || ext == "docx"){
                        str = "{{asset('images/file_doc.jpg')}}";
                }else{
                        str = "{{asset('images/file_other.jpg')}}";
                }
                }
                return str;
            },
            urlFile(file){                
                 return str = "{{ URL::to('/') }}" + file;
            }
        },
        computed:{ 
           
        },
        methods: {
            docVisible: function (doc,p) {
                console.log(doc);
                let d= [];
                let show = false;
                if(this.auth_role == 1){
                    show = false;
                }else{
                if(doc.permissions != null){
                    dp = doc.permissions.find( item => item.user === this.auth_user);
                    
                    switch(p){
                        case 'r':
                        show = (dp.r == 1)? false:true;
                        break;
                        case 'u':
                        show = (dp.u == 1)? false:true;
                        break;
                        case 'd':
                        show = (dp.d == 1)? false:true;
                        break;
                    }
                }
                }
                
                
                 return {                 
                    'd-none': show
                }
                    
            },
            hideModal(modal) {
                this.borrar();
                $('#'+modal).modal('hide');
            },
            borrar(){
                this.newDocument = { 'user_id': {{Auth::Id()}} ,'loveones_id': {{ $loveone->id}},'lockbox_types_id': '', 'name': '', 'description' : '', 'file' : '', 'status' : 1 };
                this.fillDocument = { 'id': '' , 'user_id': {{Auth::Id()}} ,'loveones_id': {{ $loveone->id}},'lockbox_types_id': '', 'name': '', 'description' : '', 'file' : '', 'status' : '' };
                this.errors = [];
                this.create_type = false;
                this.permissions = [];
            },
            getDoc(event){
                this.newDocument.file = event.target.files[0];
                this.fillDocument.file = event.target.files[0];
                $('#ffile').html(event.target.files[0].name);
            },
            getDocuments: function() {
                var url = '{{ route("lockbox",$loveone_slug) }}';
                axios.get(url).then(response => {
                    this.types = response.data.types;
                    
                    //this.getPermissions (response.data.documents);

                    this.documents = response.data.documents;  
                    this.lastDocuments = response.data.lastDocuments;  
                    this.careteam = response.data.careteam;
                    this.auth_role = response.data.isAdmin;
                    console.log(response.data);

                }).then( data => {
                    this.creaSlide();
                });
            },
            showM: function(type,doc) {
                this.newDocument.lockbox_types_id = type;
                if(doc == null){
                    this.buildPermission();
                    $('#createModal').modal('show');
                }else if(doc.file){
                    this.viewDocument(doc.file);
                }else{
                    this.newDocument.name = doc.name;
                    this.newDocument.description = doc.description;
                    this.create_type = true;
                    this.buildPermission();
                    $('#createModal').modal('show');
                }

            },
            createDocument: function() {
                var formData = new FormData();                
                formData.append('id', this.newDocument.id);
                formData.append('user_id', this.newDocument.user_id);
                formData.append('loveones_id', this.newDocument.loveones_id);
                formData.append('lockbox_types_id', this.newDocument.lockbox_types_id);
                formData.append('name', this.newDocument.name);
                formData.append('description', this.newDocument.description);
                formData.append('file', this.newDocument.file);
                formData.append('status', this.newDocument.status);
                formData.append('permissions', JSON.stringify(this.permissions));

                $('#createModal .btn-submit').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Saving...').attr('disabled', true);
                
                var url = "{{route('lockbox.store')}}";
                
                axios.post(url, formData,{ 
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                    }).then(response => {
                    if( response.data.success == true ){
                        this.getDocuments();
                        this.borrar();
                                msg = 'The Document has created';
                                icon = 'success';
                                
                    } else {
                                msg = 'There was an error. Please try again';
                                icon = 'error';
                    }
                            
                    swal(msg, "", icon);

                    $('#createModal').modal('hide');
                }).catch(error => {                    
                    this.errors = error.response.data;
                    console.log(error);
                });
            },

            editDocument: function() {
                var formData = new FormData();
                
                formData.append('id', this.fillDocument.id);
                formData.append('user_id', this.fillDocument.user_id);
                formData.append('loveones_id', this.fillDocument.loveones_id);
                formData.append('lockbox_types_id', this.fillDocument.lockbox_types_id);
                formData.append('name', this.fillDocument.name);
                formData.append('description', this.fillDocument.description);
                formData.append('file', this.fillDocument.file);
                formData.append('status', this.fillDocument.status);
                formData.append('permissions', JSON.stringify(this.permissions));

                var url = "{{route('lockbox.update')}}";
                axios.post(url, formData,{ 
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                    }).then(response => {
                    if( response.data.success == true ){
                        this.getDocuments();
                        this.borrar();
                                msg = 'The Document has updated';
                                icon = 'success';
                                
                    } else {
                                msg = 'There was an error. Please try again';
                                icon = 'error';
                    }
                            
                    swal(msg, "", icon);
                    $('#editModal').modal('hide');
                }).catch(error => {                    
                    this.errors = error.response.data;
                    console.log(error);
                });
            },
            deleteDocument: function(doc){
                var url = "{{route('lockbox.delete')}}";

                swal({
                    title: "Warning",
                    text: "Are you sure delete this document?",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        "Yes, I'm sure!"
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if(isConfirm){
                        data = {
                            id_doc: doc.id,
                        };
                        axios.post(url, data).then(response => {
                            console.log(response.data);
                            if( response.data.success == true ){
                                msg = 'Document deleted';
                                icon = 'success';
                                
                            } else {
                                msg = 'There was an error. Please try again';
                                icon = 'error';
                            }
                            lockbox.getDocuments();
                            swal(msg, "", icon);
                        });
                    }
                });
            },
            viewDocument: function(doc){   
                this.fillDocument.id          = doc.id;
                this.fillDocument.user_id     = doc.user_id;
                this.fillDocument.loveones_id = doc.user_id;
                this.fillDocument.lockbox_types_id = doc.lockbox_types_id;
                this.fillDocument.name        = doc.name;
                this.fillDocument.description = doc.description;
                this.fillDocument.file        = doc.file;
                this.fillDocument.status      = doc.status;

                this.getPermissions(doc.permissions);
                
                $('#editModal').modal('show');
            },
            getPermissions: function (arr){
                this.buildPermission();
                if(arr){
                    arr.forEach((item, i) => {                
                        let p = { 'user' : item.user, 'r' : item.r , 'u': item.u, 'd': item.d};
                        this.permissions[i].r =  item.r;
                        this.permissions[i].u =  item.u;
                        this.permissions[i].d =  item.d;
                        this.careteam[i].permissions = p;
                        if(item.user == this.auth_user){
                            this.docPermissions = p;
                        }
                    });
                }
            },
            assignPermission: function (t,u){
                let i = this.permissions.findIndex( item => item.user === u);
                let d = this.permissions.find( item => item.user === u);
                
                switch(t){
                    case "r":
                        d.r = d.r == 1 ? 0 :1;
                        break;
                    case "u":
                        d.u = d.u == 1 ? 0 :1;
                        break;
                    case "d":
                        d.d = d.d == 1 ? 0 :1;
                        break;
                }
                
                this.permissions[i] = d;
                
            },
            buildPermission: function (){
                this.permissions = [];
                for(var i = 0, len = this.careteam.length; i < len; i++) {
                    if( this.careteam[i].role === "admin" ){
                     p ={ 'user' : this.careteam[i].id, 'r' : 1 , 'u': 1, 'd': 1};
                    }else{
                        p ={ 'user' : this.careteam[i].id, 'r' : 0 , 'u': 0, 'd': 0};
                    }
                    this.permissions.push(p);
                }
            },
            creaSlide: function (){
                $('.carrusel').slick({
            centerMode: true,
            centerPadding: '10px',
            slidesToShow: 3,
            adaptiveHeight: false,
            autoplay: true,
            arrows:true,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                }
            ]
        });
            }

        }
    });

    $(document).ready(function(){
        $(".tabs").click(function(){
            
            $(".tabs").removeClass("active");
            $(".tabs h6").removeClass("font-weight-bold");
            $(".tabs h6").addClass("text-muted");
            $(this).children("h6").removeClass("text-muted");
            $(this).children("h6").addClass("font-weight-bold");
            $(this).addClass("active");

            current_fs = $(".active");

            next_fs = $(this).attr('id');
            next_fs = "#" + next_fs + "1";

            $("fieldset").removeClass("show");
            $(next_fs).addClass("show");

            current_fs.animate({}, {
                step: function() {
                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    next_fs.css({
                        'display': 'block'
                    });
                }
            });
        });
    });
</script>
@endpush