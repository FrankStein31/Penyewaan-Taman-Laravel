<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('dashboard') }}">ST</a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-fire"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if(Auth::user()->role === 'admin')
            <li class="menu-header">Master Data</li>
            <li class="{{ Request::is('users*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="{{ Request::is('taman*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('taman.index') }}">
                    <i class="fas fa-tree"></i>
                    <span>Taman</span>
                </a>
            </li>
        @endif
        
        <li class="menu-header">Transaksi</li>
        <li class="{{ Request::is('penyewaan*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('penyewaan.index') }}">
                <i class="fas fa-calendar"></i>
                <span>Penyewaan</span>
            </a>
        </li>
    </ul>
</aside> 