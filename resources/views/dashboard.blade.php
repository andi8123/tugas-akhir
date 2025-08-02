<!-- resources/views/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="">
        <h1>Welcome to the Dashboard</h1>
        @php
            $role = session('role', []);
            $roleName = 'User'; // default
            if (!empty($role)) {
                if (!empty($role['is_mhs'])) {
                    $roleName = 'Mahasiswa';
                } elseif (!empty($role['is_doswal'])) {
                    $roleName = 'Dosen Wali';
                } elseif (!empty($role['is_dosen'])) {
                    $roleName = 'Dosen';
                } elseif (!empty($role['is_prodi'])) {
                    $roleName = 'Prodi';
                } elseif (!empty($role['is_admin'])) {
                    $roleName = 'Admin';
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

        
        @if ($roleName !== 'Mahasiswa' && $account)
            {{-- Tampilan untuk semua Non-Mahasiswa --}}
            <div class="card shadow-sm border-0 mb-4"
                style="background: linear-gradient(135deg, #fff3f0, #ffffff); border-radius: 1rem;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="ms-3">
                            <h4 class="mb-0">
                                {{ $profile['nama_dan_gelar'] ?? ($dosen['nama'] ?? ($profile['nama'] ?? '-')) }}</h4>
                            <small class="text-muted">Username: {{ $account['username'] ?? '-' }}</small><br>
                            <span class="badge bg-success mt-1">{{ $roleName }}</span>
                        </div>
                    </div>

                    <div class="row text-muted" style="font-size: 0.95rem;">
                        <div class="col-md-6 mb-2">
                            <i data-feather="user" class="me-2 text-danger"></i>
                            <strong>Nama:</strong> {{ $profile['nama'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="book-open" class="me-2 text-danger"></i>
                            <strong>Gelar:</strong> {{ $profile['gelar'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="code" class="me-2 text-danger"></i>
                            <strong>Kode Dosen:</strong> {{ $profile['kd_dosen'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="user-check" class="me-2 text-danger"></i>
                            <strong>Jenis Dosen:</strong> {{ $profile['jns_dosen'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="shield" class="me-2 text-danger"></i>
                            <strong>Status Dosen:</strong> {{ $profile['sts_dosen'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="map-pin" class="me-2 text-danger"></i>
                            <strong>Jurusan ID:</strong> {{ $profile['jur_id'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="briefcase" class="me-2 text-danger"></i>
                            <strong>Kode Jabatan:</strong> {{ $profile['kd_jab'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="dollar-sign" class="me-2 text-danger"></i>
                            <strong>Honor per SKS:</strong> {{ $profile['honor_per_sks'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="id-card" class="me-2 text-danger"></i>
                            <strong>NIDN:</strong> {{ $profile['nidn'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="type" class="me-2 text-danger"></i>
                            <strong>Inisial:</strong> {{ $profile['inisial'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="image" class="me-2 text-danger"></i>
                            <strong>Profile HT:</strong> {{ $profile['profile_ht'] ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <i data-feather="award" class="me-2 text-danger"></i>
                            <strong>Golongan:</strong> {{ $profile['golongan'] ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <div class="row mb-4">
            @if (session('role')['is_admin'] ?? false)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-primary"
                        style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
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
                    <div class="card shadow-sm border-success"
                        style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
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
                    <div class="card shadow-sm border-danger"
                        style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
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
            @endif

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
