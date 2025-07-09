@php
    $menuItems = [        
        ['route' => 'user.pengajuan.index', 'icon' => 'clock', 'text' => 'Riwayat Pengajuan'],
        ['route' => 'data-pengajuan.tambahIndex', 'icon' => 'file-text', 'text' => 'Buat Pengajuan'],
    ];

    $jenisPengajuan = [
        ['route' => 'data-pengajuan.tambah', 'text' => 'Pengajuan Magang'],
        ['route' => 'data-pengajuan.tambah', 'text' => 'Pengajuan Skripsi'],
        ['route' => 'data-pengajuan.tambah', 'text' => 'Pengajuan Cuti'],
        ['route' => 'data-pengajuan.tambah', 'text' => 'Pengajuan Kerja Praktik'],
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

        <li class="nav-item nav-item-sidebar {{ Str::contains(Route::currentRouteName(), 'dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard.user') }}" class="nav-link">
                <i data-feather="home" class="sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>

        @foreach ($menuItems as $menu)
            <li class="nav-item nav-item-sidebar {{ Str::contains(Route::currentRouteName(), $menu['route']) ? 'active' : '' }}">
                <a href="{{ route($menu['route']) }}" class="nav-link">
                    <i data-feather="{{ $menu['icon'] }}" class="sidebar-icon"></i>
                    <span class="sidebar-text">{{ $menu['text'] }}</span>
                </a>
            </li>
        @endforeach

        {{-- Accordion Jenis Pengajuan --}}
        {{-- <li class="nav-item nav-item-sidebar">
            <button class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#jenisPengajuanMenu">
                <span><i data-feather="file-text" class="sidebar-icon"></i> Jenis Pengajuan</span>
                <i data-feather="chevron-down"></i>
            </button>
            <ul id="jenisPengajuanMenu" class="collapse nav flex-column ms-3">
                @foreach ($jenisPengajuan as $pengajuan)
                    <li class="nav-item">
                        <a href="{{ route($pengajuan['route']) }}" class="nav-link">{{ $pengajuan['text'] }}</a>
                    </li>
                @endforeach
            </ul>
        </li> --}}

        <li class="nav-item nav-item-sidebar">
            <a onclick="logout()" href="#" class="nav-link">
                <i data-feather="log-out" class="sidebar-icon"></i>
                <span class="sidebar-text">Log out</span>
            </a>
        </li>
    </ul>
</nav>
