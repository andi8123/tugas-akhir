@extends('layouts.admin', [
    'role' => 'user',
])

@section('title', 'Tambah Pengajuan')

@section('content')
    <div class="container mt-4">
        <div class="card p-4">
            <a href="{{ route('user.pengajuan.index') }}" class="btn mb-3 d-flex align-items-center gap-2 px-0">
                <i data-feather="arrow-left" style="width: 18px; height: 18px;"></i>
                Kembali
            </a>

            <form method="POST" action="{{ route('user.pengajuan.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Nama Surat (readonly from master_surat) --}}
                <div class="mb-3">
                    <label for="nama_surat" class="form-label">Nama Surat</label>
                    <input type="text" id="nama_surat" class="form-control"
                        value="{{ $masterPengajuan->master_surat->nama_surat }}" readonly>
                </div>

                {{-- Hidden master_pengajuan_id --}}
                <input type="hidden" name="master_pengajuan_id" value="{{ $masterPengajuan->id }}">

                {{-- Dynamic Inputs --}}
                <div class="mb-3">
                    <label class="form-label">Form Pengajuan</label>
                    @foreach ($masterPengajuan->pengajuan_pertanyaan as $index => $pertanyaan)
                        <div class="mb-3">
                            <label class="form-label">{{ $pertanyaan->pertanyaan }}</label>
                            @if ($pertanyaan->jenis_input === 'text')
                                @php
                                    $defaultJawaban = '';

                                    switch ($pertanyaan->pertanyaan) {
                                        case 'NIM':
                                            $defaultJawaban = $pengajuan->user->nim ?? '';
                                            break;
                                        case 'Nama':
                                            $defaultJawaban = $pengajuan->user->nama_mahasiswa ?? '';
                                            break;
                                        case 'Email':
                                            $defaultJawaban = $pengajuan->user->email ?? '';
                                            break;
                                        case 'Jurusan':
                                            $defaultJawaban = $pengajuan->user->jurusan ?? '';
                                            break;
                                        case 'Tanggal Lahir':
                                            $defaultJawaban = $pengajuan->user->nama_mahasiswa ?? '';
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                @endphp
                                <input type="text" name="jawaban[{{ $index }}][jawaban]" class="form-control"
                                    required>
                            @elseif ($pertanyaan->jenis_input === 'file')
                                <input type="file" name="jawaban[{{ $index }}][jawaban]" class="form-control"
                                    required>
                            @endif
                            <input type="hidden" name="jawaban[{{ $index }}][pertanyaan_id]"
                                value="{{ $pertanyaan->id }}">
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary">Ajukan</button>
                <a href="{{ route('master-surat.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
