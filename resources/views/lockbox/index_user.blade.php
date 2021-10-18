@extends('layouts.app')

@section('content')
<div class="container"  id="lockbox">
    <div class="row justify-content-center">
        <div class="col-12">
            <h4>RECENT DOCUMENTS</h4>            
                <div class="carrusel">
                    <div v-for="doc in lastDocuments" v-on:click="viewDocument(doc)" >
                        <img :src="doc.file|isImage" class="carrusel-doc">
                        <h5 class="t2 text-center">@{{ doc.name }}</h5>
                    </div>                    
                </div>            
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <h4>ESSENTIAL DOCUMENTS</h4>
        </div>
                <div v-for="doc in types" v-if="doc.asFile" v-on:click="viewDocument(doc)" :class="doc.asFile ? 'si' : 'no' " class="card document-card col-sm-12 col-md-5 col-lg-5 mr-4  align-middle"  >
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
        <div class="card document-card col-sm-12 col-md-5 col-lg-5 mr-4 align-middle"  v-for="doc in documents" v-on:click="viewDocument(doc)" >
            <div class="card-body">
                <h5 class="card-title t1">@{{ doc.name }}</h5>
                <span class="card-text t2">@{{ doc.created_at | formatDate }}</span>
            </div>
        </div>  
        
    </div>    

    @include('lockbox.view_modal')
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
            img_url: "{{asset('images/no_photo.jpg')}}",
            lastDocuments: [],
            documents: [],
            types:[],
            document: {
                id: 0,
                user_id: {{Auth::Id()}},
                lockbox_types_id: '',
                loveones_id: {{ $loveone->id }},
                name: '',
                description:'',
                file:'',                
                status: 1,
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
        methods: {
            viewDocument(doc){  
                console.log(doc);              
                this.document.id               = doc.id;
                this.document.user_id          = doc.user_id;
                this.document.loveones_id      = doc.loveones_id;
                this.document.lockbox_types_id = doc.lockbox_types_id;
                this.document.name             = doc.name;
                this.document.description      = doc.description;
                this.document.file             = doc.file;
                this.document.status           = doc.status;
                this.document.permissions      = doc.permissions;                
                
                
                $('#viewModal').modal('show');
            },
            getDocuments: function() {
                var url = '{{ route("lockbox",$loveone_slug) }}';
                axios.get(url).then(response => {
                    this.types = response.data.types;
                    this.documents = response.data.documents;
                    this.lastDocuments = response.data.lastDocuments;
                    
                    //console.table(response.data);
                }).then ( () => {
                    this.creaSlide();
                });
            },
            
            hideModal(modal) {
                $('#'+modal).modal('hide');
            },
            creaSlide: function (){
                //$('.carrusel').slick('init');
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


</script>
@endpush