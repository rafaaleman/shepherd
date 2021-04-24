<div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($loveones as $lo)
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" aria-label="Slide 1" {{ ($loop->first) ? 'class="active" aria-current="true"' : '' }}></button>
        @endforeach
    </div>
    <div class="carousel-inner">


        @foreach ($loveones as $lo)
            <div class="carousel-item active">
                <img src="{{ (!empty($lo->photo) && $lo->photo != null ) ? $lo->photo : asset('public/img/no-avatar.png')}}" class="d-block" alt="{{ strtoupper($lo->firstname) }} {{ strtoupper($lo->lastname) }}">
                <div class="carousel-caption d-none d-md-block">
                    <h5>{{ strtoupper($lo->firstname) }} {{ strtoupper($lo->lastname) }}</h5>
                    <p>{{ $lo->relationshipName }}</p>
                </div>
            </div>
        @endforeach


    </div>
    @if (sizeof($loveones) > 1)
    
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>

    @endif
</div>