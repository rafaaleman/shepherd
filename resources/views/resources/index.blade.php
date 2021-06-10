@extends('layouts.app')

@section('content')
@php
//dump($topics['articles']);
@endphp
<div class="container" id="topics">

    <h5 class="mb-3 font-weight-bolder text-dark">Trending topics</h5>
    @if($topics['totalResults'] > 0)
    <div class="row row-cols-1 row-cols-md-3 ">
        @foreach($topics['articles'] as $cve => $article)

        <div class="col mb-4">
            <div class="card ">
                <a href="{{$article->url}}" class="text-decoration-none" target="_blank">
                    <img src="{{$article->urlToImage}}" class="card-img-top" alt="{{$article->title}}">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bolder text-dark">{{$article->title}}</h5>
                        <p class="card-text">by <small class="btn-link font-weight-bolder text-decoration-none">{{$article->author}}</small></p>
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

<style>

</style>

@endpush
@push('scripts')
<script>

</script>
@endpush