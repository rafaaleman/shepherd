@extends('layouts.app')

@section('content')
<div class="container"  id="lockbox">
    <div class="row justify-content-center">
        <div class="col-12">
            <h4>RECENT DOCUMENTS</h4>
            <div id="carouselExampleControls" class="carousel slide justify-content-center" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-20" width="300px" src="https://i.stack.imgur.com/y9DpT.jpg" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-20" width="300px" src="https://i.stack.imgur.com/y9DpT.jpg" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-20" width="300px" src="https://i.stack.imgur.com/y9DpT.jpg" alt="Third slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <h4>MUST-HAVE DOCUMENTS</h4>
        </div>
                <div v-for="doc in types" v-if="doc.required == 1" v-on:click="showM(doc.lockbox_types_id,doc.file)" :class="doc.asFile ? 'si' : 'no' " class="card document-card col-sm-12 col-md-5 col-lg-5 mr-4  align-middle">
                    <div class="card-body">
                        <h4 class="card-title t1">@{{ doc.name }}</h4>
                        <p class="card-text t2">@{{ doc.description}}</p>
                    </div>
                </div>
        
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <h4>ALL DOCUMENTS</h4>
        </div>
        <div class="card document-card col-sm-12 col-md-5 col-lg-5 mr-4 align-middle" v-for="doc in documents" v-on:click="viewDocument(doc)">
            <div class="card-body">
                <h5 class="card-title t1">@{{ doc.name }}</h5>
                
                <div class="dropdown">                
                    <i class="fa fa-ellipsis-v float-right mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button"></i>                
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" v-on:click="deleteDocument(doc)" >Delete</a>
                </div>
                </div>
                <span class="card-text t2">@{{ doc.created_at | formatDate }}</span>
            </div>
        </div>  
        
        <div class="col-12 mt-4 text-center">
            <a href="#!" class="btn btn-sm btn-submit"  v-on:click="showM(4,null)">Add New Document</a>
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

.document-card{
    margin-bottom: .5rem;
}
.document-card .card-body{
    padding: 10px 0px 10px 50px;
}

.document-card.si{
  font-family: Gotham;
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
  font-family: Gotham;
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
    font-family: Gotham;
    font-size: 12px;  
}

.btn-submit{    
  color: #FFFFFF;

  padding: 12px 21px;
  border-radius: 24px;
  background-color: #369bb6;
}
</style>
@endpush

@push('scripts')
<script>
    
   const lockbox = new Vue ({
        
        el: '#lockbox',
        created: function() {
            console.log('lockbox');
            this.getDocuments();
        },
        data: {
            documents: [],
            types:[],
            action: '',
            newDocument: {
                user_id: {{Auth::Id()}},
                lockbox_types_id: '',
                name: '',
                description:'',
                file:'',                
                status: 1,
            },
            fillDocument: {
                id: '',
                user_id: {{Auth::Id()}},
                lockbox_types_id: '',
                name: '',
                description:'',
                file:'',                
                status: '',
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
                    return value[0];
                }
            }
        },
        computed:{ 
        },
        methods: {
            borrar(){
                this.newDocument = { 'user_id': {{Auth::Id()}} ,'lockbox_types_id': '', 'name': '', 'description' : '', 'file' : '', 'status' : 1 };
                this.fillDocument = { 'id': '' , 'user_id': {{Auth::Id()}} ,'lockbox_types_id': '', 'name': '', 'description' : '', 'file' : '', 'status' : '' };
                this.errors = [];            
            },
            getDoc(event){
                this.newDocument.file = event.target.files[0];
                this.fillDocument.file = event.target.files[0];
            },
            getDocuments: function() {
                var url = '{{ route("lockbox",$loveone_slug) }}';
                axios.get(url).then(response => {
                    this.types = response.data.types;
                    this.documents = response.data.documents;                    
                console.log(response.data);
                });
                console.log(this.documents);
            },
            showM: function(type,doc) {
                this.newDocument.lockbox_types_id = type;
                console.log(doc);
                if(doc){
                    this.viewDocument(doc);
                }else{
                    $('#createModal').modal('show');
                }

            },
            createDocument: function() {
                var formData = new FormData();
                
                formData.append('id', this.newDocument.id);
                formData.append('user_id', this.newDocument.user_id);
                formData.append('lockbox_types_id', this.newDocument.lockbox_types_id);
                formData.append('name', this.newDocument.name);
                formData.append('description', this.newDocument.description);
                formData.append('file', this.newDocument.file);
                formData.append('status', this.newDocument.status);
                
                var url = "lockbox";
                axios.post(url, formData,{ 
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                    }).then(response => {

                    console.log(response);
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
                formData.append('lockbox_types_id', this.fillDocument.lockbox_types_id);
                formData.append('name', this.fillDocument.name);
                formData.append('description', this.fillDocument.description);
                formData.append('file', this.fillDocument.file);
                formData.append('status', this.fillDocument.status);
                
                var url = "{{route('lockbox.update')}}";
                axios.post(url, formData,{ 
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                    }).then(response => {

                    console.log(response);
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
                this.fillDocument.lockbox_types_id = doc.lockbox_types_id;
                this.fillDocument.name        = doc.name;
                this.fillDocument.description = doc.description;
                this.fillDocument.file        = doc.file;
                this.fillDocument.status      = doc.status;
                $('#editModal').modal('show');
        }
        }
    });
</script>
@endpush