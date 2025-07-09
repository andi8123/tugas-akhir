<!-- resources/views/dashboard.blade.php -->
@extends('layouts.admin', [
    'role' => 'user',
])

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
                            {{ isset($profile['alamat']) ? str_replace("\n", ', ', $profile['alamat']) : '-' }}
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
                            <i data-feather="inbox" class="text-primary" style="width: 40px; height: 40px;"></i>
                        </div>
                        <h6 class="text-muted">Total Pengajuan</h6>
                        <h3 class="text-primary">{{ $data['pengajuanCount'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-success" style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-8px) scale(1.03)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.12)'"
                    onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i data-feather="check-circle" class="text-success" style="width: 40px; height: 40px;"></i>
                        </div>
                        <h6 class="text-muted">Pengajuan Diterima</h6>
                        <h3 class="text-success">{{ $data['pengajuanDiterima'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-danger" style="transition: transform 0.3s ease, box-shadow 0.3s ease;"
                    onmouseover="this.style.transform='translateY(-8px) scale(1.03)'; this.style.boxShadow='0 10px 20px rgba(0, 0, 0, 0.12)'"
                    onmouseout="this.style.transform='none'; this.style.boxShadow='none'">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i data-feather="x-circle" class="text-danger" style="width: 40px; height: 40px;"></i>
                        </div>
                        <h6 class="text-muted">Pengajuan Ditolak</h6>
                        <h3 class="text-danger">{{ $data['pengajuanDitolak'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
