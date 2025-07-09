@php
    $menuItems = [
        ['route' => 'master-surat.index', 'icon' => 'file-text', 'text' => 'Master Surat', 'admin_only' => true],
        // ['route' => 'master-role.index', 'icon' => 'users', 'text' => 'Master Role'],
        ['route' => 'master-pengajuan.index', 'icon' => 'user-check', 'text' => 'Master Pengajuan', 'admin_only' => true],
        ['route' => 'data-pengajuan.index', 'icon' => 'database', 'text' => 'Data Pengajuan'],
    ];
@endphp

{{-- Sidebar --}}
<nav id="sidebar" class="d-flex flex-column p-3 sidebar justify-content-between">
    <ul class="nav flex-column gap-2">
        <li class="nav-item">
            <div class="sidebar-logo d-flex align-items-center gap-3 border-bottom">
                <img height="60" src="/images/logo.png" alt="">
                <div class="">
                    <p class="mb-0 title">SIPENA</p>
                    <p class="mb-0 subtitle">Manajemen Akademik</p>
                </div>
            </div>
        </li>

        <li class="nav-item nav-item-sidebar mt-3 fz-12">
            <div>Menu Utama</div>
        </li>

        <li
            class="nav-item nav-item-sidebar {{ Str::contains(Route::currentRouteName(), 'dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i data-feather="home" class="sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>

        @foreach ($menuItems as $menu)
            @if (!isset($menu['admin_only']) || session('role.is_admin'))
                <li
                    class="nav-item nav-item-sidebar {{ Str::contains(Route::currentRouteName(), $menu['route']) ? 'active' : '' }}">
                    <a href="{{ route($menu['route']) }}" class="nav-link">
                        <i data-feather="{{ $menu['icon'] }}" class="sidebar-icon"></i>
                        <span class="sidebar-text">{{ $menu['text'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach

        <li class="nav-item nav-item-sidebar">
            <a onclick="logout()" href="#" class="nav-link">
                <i data-feather="log-out" class="sidebar-icon"></i>
                <span class="sidebar-text">Log out</span>
            </a>
        </li>
    </ul>
</nav>
