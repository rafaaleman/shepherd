@extends('layouts.app')

@section('content')
@php
//dump($topics['articles']);
@endphp
<div class="container" id="topics">

    <h5 class="mb-3 font-weight-bolder text-dark">Trending topics</h5>
    <div class="d-flex mb-4">
        <div class="input-group">
            <input type="text" class="form-control h-100" style="border-radius: 50rem 0 0 50rem !important;" v-model="search.keyword" aria-label="fbg">
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary" v-on:click="searchArticles"><i class="fas fa-search" ></i></button>
            </div>
        </div>

    </div>

    <div class="loading-articles text-center">
        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"> </span> Loading articles...
    </div>
    <div class="text-center mb-2" v-if="articles.length == 0 && search.keyword != ''">
            <b> @{{articles.length}}</b> Results for '<b>@{{search.keyword}}</b>'
    </div>
        <div class="slide-articles" >
            <div class="col mb-4" v-for="article in articles">
                <div class="card ">
                    <a :href="article.url" class="text-decoration-none" target="_blank">
                        <template v-if="article.urlToImage != ''">
                            <img :src="article.urlToImage" class="card-img-top" :alt="article.title">
                        </template>
                        <template v-else>
                            <video :src="article.url" autoplay loop></video>
                        </template>

                        <div class="card-body">
                            <h5 class="card-title font-weight-bolder text-dark">@{{article.title}}</h5>
                            <template v-if="article.author != ''">
                                <p class="card-text">by <small class="btn-link font-weight-bolder text-decoration-none">@{{article.author}}</small></p>
                            </template>
                        </div>
                    </a>
                </div>
            </div>
        </div>
  
        
    
    <h5 class="mb-3 font-weight-bolder text-dark">Based on your Medical History</h5>
    <div class="mb-3">
        @foreach($conditions_loveone as $condition)
        <span class="badge badge-pill p-2 bg-white shadow-sm txt-black" style="font-size:1em;">{{$condition}}</span>

        @endforeach
    </div>
    @if($topics['totalResults'] > 0)
    <div class="row row-cols-1 row-cols-md-3 ">
        @foreach($topics['articles'] as $cve => $article)
            @php
            //dump($article);
        @endphp
        <div class="col mb-4">
            <div class="card ">
                <a href="{{$article->url}}" class="text-decoration-none" target="_blank">
                    @if(!empty($article->urlToImage))
                    <img src="{{$article->urlToImage}}" class="card-img-top" alt="{{$article->title}}">
                    @else
                    <video src="{{$article->url}}" autoplay loop></video>
                    <!--iframe src="{{$article->url}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe-->

                    @endif
                    <div class="card-body">
                        <h5 class="card-title font-weight-bolder text-dark">{{$article->title}}</h5>
                        @if(!empty($article->author))
                            <p class="card-text">by <small class="btn-link font-weight-bolder text-decoration-none">{{$article->author}}</small></p>

                        @endif
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class=" w-100 text-center">No topics found...</div>
    @endif







</div>

@endsection
@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
// Add the new slick-theme.css if you want the default styling
<link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
<style>

</style>

@endpush
@push('scripts')

<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script>

$('.slide-articles').hide(); 

var app = new Vue({
    el: '#topics',
    data:{
        articles: [],
        search: {keyword: ''},
        noMember: false,
        info:[]
    },
    mounted: function(){
       
        
    },
    created: function(){
        this.searchArticlesIni();

    },
    updated:function(){
        
    },
  
    methods:{
        searchArticles: function() {
            if(app.search.keyword == ''){
                $("#search").focus();
                return false;
            }

            $('.searchBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>' + $('.searchBtn').data('loading-text')).attr('disabled', true);

            $('.slide-articles').slick('unslick');
            $('.loading-articles').show(); 
            $('.slide-articles').hide();
            app.articles=[];
            var keyword = app.toFormData(app.search);
            
            var url = '{{ route("resources.search") }}';
            axios.post(url, keyword)
                .then(function(response){
                    app.articles = response.data.topics.articles;
                    $('.loading-articles').hide(); 
                    $('.slide-articles').show();
                    $(document).ready(function(){
                        $('.slide-articles').slick({
                            infinite: true,
                        slidesToShow: 2,
                        slidesToScroll: 2
                        });
                        $('.searchBtn').html('<i class="fas fa-search" ></i> ').attr('disabled', false);
                    });

                    
                  
                });
            

        },
        searchArticlesIni: function() {
            //app.articles=[];
            var keyword = '';
            var url = '{{ route("resources.ini") }}';
            axios.post(url, keyword)
                .then(function(response){
                    app.articles = response.data.topics.articles;
                    console.log(response);

                    $('.loading-articles').hide(); 
                    $('.slide-articles').show();  
                    $(document).ready(function(){
                        $('.slide-articles').slick({
                            infinite: true,
                        slidesToShow: 2,
                        slidesToScroll: 2
                        });
                    });
                  
                });
        },
  
      /*  fetchMembers: function(){
            app.articles=[];
            axios.post('action.php')
                .then(function(response){
                    app.articles = response.data.articles;
                });
        },*/
  
        toFormData: function(obj){
            var form_data = new FormData();
            for(var key in obj){
                form_data.append(key, obj[key]);
            }
            return form_data;
        },
  
    }
});
</script>
@endpush