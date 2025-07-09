@extends('layouts.admin')

@section('title', 'Detail Data Pengajuan')

@section('content')
    <div class="container mt-4">
        {{-- @dd($pengajuan) --}}
        {{-- @dump($pengajuan) --}}

        <a href="{{ route('data-pengajuan.index') }}" class="btn mb-3 d-flex align-items-center gap-2">
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
                    <td style="vertical-align: middle"><span>Nomor Surat</span></td>
                    <td>
                        @if ($nomorSurat)
                            {{ $nomorSurat['nomor_surat'] }}
                        @else
                            <form class="flex items-center gap-2 d-flex"
                                action="{{ route('data-pengajuan.add-nomor-surat', ['id' => $pengajuan->id]) }}"
                                method="POST">
                                @csrf
                                <input name="nomor_surat" type="text" class="form-control"
                                    placeholder="Masukkan Nomor Surat">
                                <button class="btn btn-primary w-max flex-shrink-0">Tambah Nomor Surat</button>
                            </form>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Data</strong></td>
                </tr>
                @foreach ($pengajuan->pengajuan_jawaban as $key => $value)
                    <tr>
                        <td><span>{{ $value->pengajuan_pertanyaan->pertanyaan }}:</span></td>

                        @if ($value->pengajuan_pertanyaan->jenis_input == 'file')
                            <td>
                                <a href="{{ $value->jawaban }}"
                                    class="d-flex align-items-center gap-2 text-decoration-none">
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

            <div class="mt-3 flex items-center gap-2" style="display: flex;">
                @if ($nomorSurat && $isVerified)
                    <a href="/generate-surat/{{ $pengajuan->id }}" class="btn btn-outline-primary"><i
                            class="fas fa-download"></i> Unduh Surat</a>
                @endif

                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal">
                    <i class="fas fa-check"></i> Tolak
                </button>

                <!-- Modal -->
                <div class="modal fade" id="tolakModal" tabindex="-1" aria-labelledby="tolakModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="tolakForm" action="{{ route('data-pengajuan.verifikasi', ['id' => $pengajuan->id]) }}"
                            method="POST">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="status" value="rejected">
                            <input type="hidden" name="value" value="false">
                            @if (session()->has('role'))
                        @php
                            $roles = session('role'); // array asosiatif
                            // Ambil key yang value-nya true saja
                            $activeRoles = array_keys(array_filter($roles, fn($value) => $value === true));
                            $roleString = implode(',', $activeRoles);
                        @endphp
                        <input type="hidden" name="role" value="{{ $roleString }}">
                    @endif

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tolakModalLabel">Tolak Pengajuan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="komentar" class="form-label">Komentar</label>
                                        <textarea class="form-control" id="komentar" name="komentar" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <form class="w-fit" action="{{ route('data-pengajuan.verifikasi', ['id' => $pengajuan->id]) }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="status" value="accepted">
                    <input type="hidden" name="value" value="true">

                    @if (session()->has('role'))
                        @php
                            $roles = session('role'); // array asosiatif
                            // Ambil key yang value-nya true saja
                            $activeRoles = array_keys(array_filter($roles, fn($value) => $value === true));
                            $roleString = implode(',', $activeRoles);
                        @endphp
                        <input type="hidden" name="role" value="{{ $roleString }}">
                    @endif

                    <button class="btn btn-primary"><i class="fas fa-check"></i> Verifikasi</button>
                </form>
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
