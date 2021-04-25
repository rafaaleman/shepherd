@if ($careteam_users != null )

    <div class="card widget team">
        <div class="card-body">
            <i class="fas fa-users fa-2x"></i>
            <h5 class="card-title">CareTeam</h5>
            <p class="card-text">
                <span>{{ $careteam_users->count() }}</span> Member(s) <br>    

                <div class="pl-3 avatar-imgs">
                    @foreach ($careteam_users as $user)
                        <img src="{{ ($user->photo) ?: asset('public/img/no-avatar.png') }}" class="member-img" title="{{ $user->name . ' ' . $user->lastname}}" data-bs-toggle="tooltip" data-bs-placement="bottom">
                    @endforeach
                </div>
            </p>
        </div>
    </div>
@endif