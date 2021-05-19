<div id="homeCarousel" class="carousel slide">
    <ol class="carousel-indicators">
        @foreach ($loveones as $lo)
            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $loop->index }}" class="{{ ($loop->first) ? 'active' : '' }}"></li>
        @endforeach
    </ol>
    <div class="carousel-inner">


        @foreach ($loveones as $loveone)
            <div class="carousel-item {{ ($loop->first) ? 'active' : '' }} loveone-{{ $loveone->id }}" data-id="{{ $loveone->id }}">
                <img src="{{ (!empty($loveone->photo) && $loveone->photo != null ) ? asset($loveone->photo) : asset('public/img/no-avatar.png')}}" class="loveone-photo d-block">
                <div class="carousel-caption d-md-block">
                    <h5>{{ strtoupper($loveone->firstname) }} {{ strtoupper($loveone->lastname) }}</h5>
                    <p>{{ $loveone->relationshipName }}</p>
                    <button class="btn  btn-sm mb-2  btn-primary" @click="refreshWidgets( '{{$loveone->id}}', '{{$loveone->slug}}')">
                        Select
                    </button>
                </div>
            </div>
        @endforeach


    </div>
    @if (sizeof($loveones) > 1)
    
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

    @endif

    <div class="carousel-shadow"></div>
</div>

