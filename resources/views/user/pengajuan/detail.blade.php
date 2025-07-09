@extends('layouts.admin', [
    'role' => 'user',
])

@section('title', 'Detail Data Pengajuan')

@section('content')
    <div class="container mt-4">
        {{-- @dd($pengajuan) --}}
        {{-- @dump($pengajuan) --}}

        <a href="{{ route('user.pengajuan.index') }}" class="btn mb-3 d-flex align-items-center gap-2">
            <i data-feather="arrow-left" style="width: 18px; height: 18px;"></i>
            Kembali
        </a>

        <div class="card p-4 mb-4">
            <h5 class="fw-bold">Detail Pengajuan</h5>

            <table class="table table-borderless">
                <tr>
                    <td><span>Tanggal Pengajuan:</span></td>
                    <td>{{ date('d M Y', strtotime($pengajuan->created_at ?? now())) }}</td>
                </tr>
                <tr>
                    <td><span>Nama Surat:</span></td>
                    <td>{{ $pengajuan->master_pengajuan->master_surat->nama_surat }}</td>
                </tr>
                <tr>
                    <td><span>NIM Pengaju:</span></td>
                    <td>{{ $pengajuan->user->nim ?? '' }}</td>
                </tr>
                <tr>
                    <td><span>Nama Pengaju:</span></td>
                    <td>{{ $pengajuan->user->nama_mahasiswa }}</td>
                </tr>
                <tr>
                    <td><span>Jurusan:</span></td>
                    <td>{{ $pengajuan->user->jurusan }}</td>
                </tr>
                <tr>
                    <td><span>Email Pengaju:</span></td>
                    <td>{{ $pengajuan->user->email }}</td>
                </tr>
                <tr>
                    <td><strong>Data</strong></td>
                </tr>
                @foreach ($pengajuan->pengajuan_jawaban as $key => $value)
                    <tr>
                        <td><span>{{ $value->pengajuan_pertanyaan->pertanyaan }}:</span></td>

                        @if ($value->pengajuan_pertanyaan->jenis_input == 'file')
                            <td>                                
                                <a href="{{ $value->jawaban }}" class="d-flex align-items-center gap-2 text-decoration-none">
                                    <i data-feather="file" style="width: 18px; height: 18px;"></i>
                                    Lihat
                                </a>
                            </td>
                        @else
                            <td>{{ $value->jawaban }}</td>
                        @endif
                    </tr>
                @endforeach

            </table>

            {{-- TODO:  uncomment ketika pake data asli --}}
            @if ($isVerified) 
            <div class="mt-3 flex items-center gap-4">
                <a href="/generate-surat/{{ $pengajuan->id }}" class="btn btn-outline-primary"><i
                        class="fas fa-download"></i> Unduh Surat</a>
            </div>
         @endif
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
                            <th>Komentar</th>
                            <th>Tanggal Disetujui</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($verifikasi as $key => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    {{ App\Helpers\RoleHelper::toString($item) ?? '-' }}
                                </td>
                                <td>{{ $item['komentar'] ?? '-' }}</td>
                                <td>
                                    {{ isset($item['verified_at']) ? date('d M Y', strtotime($item['verified_at'])) : '-' }}
                                </td>
                                <td class="capitalize">
                                    @isset($item['status'])
                                        <span
                                            class="badge
                                        {{ $item['status'] == 'diterima' ? 'bg-success' : ($item['status'] == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ $item['status'] }}
                                        </span>
                                    @endisset
                                </td>
                            </tr>
                        @endforeach
                        @if (empty($verifikasi))
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
