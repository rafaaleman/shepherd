@extends('layouts.app_simple')

@section('content')
<div class="container-fluid"  id="create_loveone">

    <form id="create_lovedone_form" method="POST" action="#" style="width: 100%;" class="row" v-on:submit.prevent="createLoveone()" enctype="multipart/form-data">
        @csrf
        <div class="cards col-sm-10 col-md-8 col-lg-5 mt-5 mx-auto">
            <h3>You have reach the max number of Lovedones ;(</h3>
        </div>
    </form>
    
</div>
@endsection



@push('scripts')
<script>

$(function(){

    
});    
</script>
@endpush
