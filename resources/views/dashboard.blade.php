<!-- resources/views/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="">
        <h1>Welcome to the Dashboard</h1>
        @if ($profile && $account)
            <div class="card shadow-sm border-0 mb-4"
                style="background: linear-gradient(135deg, #f0f4ff, #ffffff); border-radius: 1rem;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="ms-3">
                            <h4 class="mb-0">{{ $profile['nama'] ?? '-' }}</h4>
                            <small class="text-muted">NIM: {{ $profile['nim'] ?? '-' }}</small><br>
                            <span class="badge bg-primary mt-1">{{ $profile['nama_jurusan'] ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="row text-muted" style="font-size: 0.95rem;">
                        <div class="col-md-6 mb-2">
                            <i data-feather="user" class="me-2 text-primary"></i>
                            <strong>Dosen Wali:</strong> {{ $profile['dosen_wali'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="calendar" class="me-2 text-primary"></i>
                            <strong>Tanggal Lahir:</strong>
                            {{ !empty($profile['tgl_lahir']) ? \Carbon\Carbon::parse($profile['tgl_lahir'])->format('d M Y') : '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="layers" class="me-2 text-primary"></i>
                            <strong>Angkatan:</strong> {{ $profile['angkatan'] ?? '-' }} | Kelas
                            {{ $profile['kelas'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="map-pin" class="me-2 text-primary"></i>
                            <strong>Tempat Lahir:</strong> {{ $profile['tmp_lahir'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="phone" class="me-2 text-primary"></i>
                            <strong>No. HP:</strong> {{ $profile['nmr_hp'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="home" class="me-2 text-primary"></i>
                            <strong>Alamat:</strong>
                            {{ !empty($profile['alamat']) ? str_replace("\n", ', ', $profile['alamat']) : '-' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-primary" style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-8px) scale(1.03)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.12)'"
                    onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i data-feather="file-text" class="text-primary" style="width: 40px; height: 40px;"></i>
                        </div>
                        <h6 class="text-muted">Total Master Pengajuan</h6>
                        <h3 class="text-primary">{{ $data['masterPengajuanCount'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-success" style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-8px) scale(1.03)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.12)'"
                    onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i data-feather="file" class="text-success" style="width: 40px; height: 40px;"></i>
                        </div>
                        <h6 class="text-muted">Total Master Surat</h6>
                        <h3 class="text-success">{{ $data['masterSuratCount'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-danger" style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-8px) scale(1.03)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.12)'"
                    onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i data-feather="inbox" class="text-danger" style="width: 40px; height: 40px;"></i>
                        </div>
                        <h6 class="text-muted">Total Pengajuan</h6>
                        <h3 class="text-danger">{{ $data['pengajuanCount'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        @php
            $role = session('role', []);
            $roleName = 'User'; // default
            if (!empty($role)) {
                if (!empty($role['is_dev'])) {
                    $roleName = 'Developer';
                } elseif (!empty($role['is_mhs'])) {
                    $roleName = 'Mahasiswa';
                } elseif (!empty($role['is_doswal'])) {
                    $roleName = 'Dosen Wali';
                } elseif (!empty($role['is_prodi'])) {
                    $roleName = 'Prodi';
                } elseif (!empty($role['is_admin'])) {
                    $roleName = 'Admin';
                } elseif (!empty($role['is_dosen'])) {
                    $roleName = 'Dosen';
                } elseif (!empty($role['is_staff'])) {
                    $roleName = 'Staff';
                } elseif (!empty($role['is_wk'])) {
                    $roleName = 'Wakil Ketua';
                } elseif (!empty($role['is_pimpinan'])) {
                    $roleName = 'Pimpinan';
                } elseif (!empty($role['is_dospem'])) {
                    $roleName = 'Dosen Pembimbing';
                } elseif (!empty($role['is_marketing'])) {
                    $roleName = 'Marketing';
                } elseif (!empty($role['is_akademik'])) {
                    $roleName = 'Akademik';
                } elseif (!empty($role['is_baak'])) {
                    $roleName = 'BAAK';
                } elseif (!empty($role['is_secretary'])) {
                    $roleName = 'Sekretaris';
                } elseif (!empty($role['is_bendahara'])) {
                    $roleName = 'Bendahara';
                } elseif (!empty($role['is_kemahasiswaan'])) {
                    $roleName = 'Kemahasiswaan';
                }
            }
        @endphp

        {{-- <p>This is your {{ $roleName }} panel.</p> --}}

        {{-- Menampilkan token dari session --}}
        @if (session('token'))
            {{-- <div class="alert alert-info">
                Token Anda: {{ session('token') }}
            </div> --}}

            @if (!empty($role))
                @if (!empty($role['is_dev']))
                    {{-- <p>Kamu login sebagai Developer</p> --}}
                @elseif(!empty($role['is_mhs']))
                    {{-- <p>Kamu login sebagai Mahasiswa</p> --}}
                @elseif(!empty($role['is_admin']))
                    {{-- <p>Kamu login sebagai Admin</p> --}}
                @endif
            @endif
        @else
            {{-- <div class="alert alert-warning">
                Tidak ada token dalam session.
            </div> --}}
        @endif
    </div>

@endsection
