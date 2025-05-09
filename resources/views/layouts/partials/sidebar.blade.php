<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">SPT</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>

            @if(auth()->user()->isAdmin())
                <li class="menu-header">Master Data</li>
                <li class="{{ Request::is('users*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i> <span>Manajemen Pengguna</span>
                    </a>
                </li>
                <li class="{{ Request::is('fasilitas*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('fasilitas.index') }}">
                        <i class="fas fa-building"></i> <span>Manajemen Fasilitas</span>
                    </a>
                </li>
                <li class="{{ Request::is('taman*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('taman.index') }}">
                        <i class="fas fa-tree"></i> <span>Manajemen Taman</span>
                    </a>
                </li>
            @endif

            <li class="menu-header">Transaksi</li>
            @if(!auth()->user()->isAdmin())
                <li class="{{ Request::is('taman') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('taman.index') }}">
                        <i class="fas fa-search"></i> <span>Cari Taman</span>
                    </a>
                </li>
            @endif
            <li class="{{ Request::is('pemesanan*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pemesanan.index') }}">
                    <i class="fas fa-calendar"></i> <span>Pemesanan</span>
                </a>
            </li>
            <li class="{{ Request::is('pembayaran*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pembayaran.index') }}">
                    <i class="fas fa-money-bill"></i> <span>Pembayaran</span>
                </a>
            </li>

            <li class="menu-header">Pengaturan</li>
            <li class="{{ Request::is('profile*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user"></i> <span>Edit Profil</span>
                </a>
            </li>
            <li>
                <a class="nav-link text-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>
</div> 