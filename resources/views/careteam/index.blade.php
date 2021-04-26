@extends('layouts.app')

@section('content')
<div class="container"  id="careteam">
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{$loveone->firstname}} {{$loveone->lastname}}</h5>
            <p class="card-text">Loveone</p>
            

            <div class="row">
                <div class="col-md-6">
                    <img src="{{ (!empty($loveone->photo) && $loveone->photo != null ) ? $loveone->photo : asset('public/img/no-avatar.png')}}" class="img-fluid">
                </div>
                
                <div class="col-md-6 members">
                    @foreach ($members as $member)
                        <div class="member">
                            <img src="{{ (!empty($member->photo) && $member->photo != null ) ? $member->photo : asset('public/img/no-avatar.png')}}" class="float-left">
                            <div class="data float-left">
                                <div class="name">{{ $member->name }} {{ $member->lastname }}</div>
                                <div class="role">{{ ucfirst($member->careteam->role) }}</div>
                            </div>
                            @if ($is_admin)
                                <a class="info float-right mr-2" href="#">
                                    <i class="fas fa-info-circle fa-2x mt-2"></i>
                                </a>
                            @endif
                            
                        </div>
                    @endforeach
                    @if ($is_admin)
                        <a href="">
                            <div class="member d-flex add">
                                <i class="fas fa-plus-circle fa-3x mr-2"></i>
                                <div class="data">
                                    <div class="name mt-2">Add New Member</div>
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection