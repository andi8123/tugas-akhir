@extends('layouts.admin')

@section('title', 'Detail Data Pengajuan')

{{-- DUMMY DATA --}}
@php
    $data = [
        (object) [
            'id' => 1,
            'role' => 'Dosen',
            'tanggal_disetujui' => '2024-08-25',
            'status' => 'Menunggu Persetujuan',
        ],
        (object) [
            'id' => 2,
            'role' => 'Kepala Program Studi',
            'tanggal_disetujui' => '2024-08-24',
            'status' => 'Disetujui',
        ],
        (object) [
            'id' => 3,
            'role' => 'Kepala Jurusan',
            'tanggal_disetujui' => '2024-08-23',
            'status' => 'Ditolak',
        ],
    ];

    $detailPengajuan = (object) [
        'nama_surat' => 'Pengajuan Magang',
        'nim_pengaju' => '2101234567',
        'nama_pengaju' => 'Naufal',
        'email_pengaju' => 'naufal@example.com',
        'tanggal_dibuat' => '2024-07-29',
        'input_surat' => [
            'Nomor Telepon' => '6281321905556',
            'Status Pengajuan' => 'Draft',
            'Keterangan' => '-',
            'File Surat' => 'https://google.com',
        ],
    ];
@endphp

@section('content')
    <div class="container mt-4">
        <a href="{{ route()->back() }}" class="btn mb-3 d-flex align-items-center gap-2">
            <i data-feather="arrow-left" style="width: 18px; height: 18px;"></i>
            Kembali
        </a>

        <div class="card p-4 mb-4">
            <h5 class="fw-bold">Detail Pengajuan</h5>

            <table class="table table-borderless">
                <tr>
                    <td><span>Tanggal Pengajuan:</span></td>
                    <td>{{ date('d M Y', strtotime($detailPengajuan->tanggal_dibuat)) }}</td>
                </tr>
                <tr>
                    <td><span>Nama Surat:</span></td>
                    <td>{{ $detailPengajuan->nama_surat }}</td>
                </tr>
                <tr>
                    <td><span>NIM Pengaju:</span></td>
                    <td>{{ $detailPengajuan->nim_pengaju }}</td>
                </tr>
                <tr>
                    <td><span>Nama Pengaju:</span></td>
                    <td>{{ $detailPengajuan->nama_pengaju }}</td>
                </tr>
                <tr>
                    <td><span>Email Pengaju:</span></td>
                    <td>{{ $detailPengajuan->email_pengaju }}</td>
                </tr>
                <tr>
                    <td><strong>Data</strong></td>
                </tr>
                @foreach ($detailPengajuan->input_surat as $key => $value)
                    <tr>
                        <td><span>{{ $key }}:</span></td>

                        @if (str_contains($value, 'https://'))
                            <td>
                                <a href="{{ $value }}" class="d-flex align-items-center gap-2 text-decoration-none">
                                    <i data-feather="file" style="width: 18px; height: 18px;"></i>
                                    Lihat
                                </a>
                            </td>
                        @else
                            <td>{{ $value }}</td>
                        @endif
                    </tr>
                @endforeach

            </table>
            <div class="mt-3">
                <button class="btn btn-primary" disabled><i class="fas fa-check"></i> Verifikasi
                </button>
            </div>
        </div>

        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="h5 fw-bold">Daftar Persetujuan</div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Role</th>
                            <th>Tanggal Disetujui</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->role }}</td>
                                <td>{{ date('d M Y', strtotime($item->tanggal_disetujui)) }}</td>
                                <td>
                                    <span
                                        class="badge
                                        {{ $item->status == 'Disetujui' ? 'bg-success' : ($item->status == 'Ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        @if (empty($data))
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data pengajuan</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
