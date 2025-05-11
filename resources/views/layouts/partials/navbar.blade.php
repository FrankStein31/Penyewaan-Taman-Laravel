<form class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
</form>

<ul class="navbar-nav navbar-right">
    <li class="dropdown">
        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            @if(auth()->user()->profile_photo)
                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Foto Profil" class="rounded-circle mr-1" width="30" height="30">
            @else
                <div class="d-inline-block">
                    <div class="avatar bg-primary text-white rounded-circle mr-1" style="width:30px;height:30px;display:inline-flex;align-items:center;justify-content:center;font-size:14px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            @endif
            <div class="d-sm-none d-lg-inline-block">
                Hi, {{ auth()->user()->name }}
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('profile.edit') }}" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Edit Profil
            </a>
            <div class="dropdown-divider"></div>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item has-icon text-danger d-flex align-items-center">
                    <i class="fas fa-sign-out-alt"></i>&nbsp;Logout
                </button>
            </form>
        </div>
    </li>
</ul>