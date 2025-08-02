@extends('layouts.admin')

@section('title', 'Edit Master Pengajuan')

@section('content')
    <div class="container mt-4">
        <div class="card p-4">
            <h5 class="card-title">Edit Master Pengajuan</h5>
            <form method="POST" action="{{ route('master-pengajuan.update', $masterPengajuan->id) }}">
                @csrf
                @method('PUT')

                {{-- Nama Pengajuan --}}
                <div class="mb-3">
                    <label for="nama_pengajuan" class="form-label">Nama Pengajuan</label>
                    <input type="text" name="nama_pengajuan" id="nama_pengajuan" class="form-control"
                        value="{{ old('nama_pengajuan', $masterPengajuan->nama_pengajuan) }}" required>
                </div>

                {{-- Input Pertanyaan (Dynamic Fields) --}}
                <div class="mb-3">
                    <label class="form-label">Input Pertanyaan</label>
                    <div id="inputPertanyaanContainer">
                        @foreach ($masterPengajuan->pengajuan_pertanyaan as $index => $pertanyaan)
                            <div class="input-group mb-2">
                                <input type="text" name="input_pertanyaan[{{ $index }}][pertanyaan]"
                                    class="form-control" placeholder="Pertanyaan" value="{{ $pertanyaan->pertanyaan }}"
                                    required>
                                <select name="input_pertanyaan[{{ $index }}][jenis_input]" class="form-select"
                                    required>
                                    <option value="text" {{ $pertanyaan->jenis_input == 'text' ? 'selected' : '' }}>Text
                                    </option>
                                    <option value="file" {{ $pertanyaan->jenis_input == 'file' ? 'selected' : '' }}>File
                                    </option>
                                </select>
                                <button type="button" class="btn btn-danger remove-input"
                                    {{ $loop->first ? 'disabled' : '' }}>−</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="addInputPertanyaan" class="btn btn-success btn-sm">+ Tambah
                        Pertanyaan</button>
                </div>

                {{-- Yang Meng-ACC --}}
                <div class="mb-3">
                    <label class="form-label">Yang Meng-ACC</label>

                    @foreach ([
            'is_admin' => 'Admin',
            'is_prodi' => 'Prodi',
            'is_doswal' => 'Dosen Wali',
            'is_dosen' => 'Dosen',
            'is_staff' => 'Staff',
            'is_wk' => 'Wakil Ketua',
            // 'is_pimpinan' => 'Pimpinan',
            'is_dospem' => 'Dosen Pembimbing',
            // 'is_marketing' => 'Marketing',
            // 'is_akademik' => 'Akademik',
            // 'is_baak' => 'BAAK',
            // 'is_secretary' => 'Sekretaris',
            // 'is_bendahara' => 'Bendahara',
            // 'is_kemahasiswaan' => 'Kemahasiswaan',
        ] as $role => $label)
                        <div class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input role-checkbox" type="checkbox" name="{{ $role }}"
                                    id="{{ $role }}" value="1" data-role="{{ $role }}"
                                    {{ $masterPengajuan->$role ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $role }}">{{ $label }}</label>
                            </div>
                            <div class="user-selection mt-2" id="{{ $role }}_user_selection"
                                style="display: {{ $masterPengajuan->$role ? 'block' : 'none' }};">
                                <label for="by_{{ $role }}_user_id" class="form-label">Pilih User
                                    {{ $label }}</label>
                                <select name="by_{{ $role }}_user_id" id="by_{{ $role }}_user_id"
                                    class="form-select user-select" {{ $masterPengajuan->$role ? 'required' : '' }}>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ isset($masterPengajuan->{'by_' . $role . '_user_id'}) && $masterPengajuan->{'by_' . $role . '_user_id'} == $user->id ? 'selected' : '' }}>
                                            {{ $user->name ?? ($user->email ?? '') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Jenis Surat Akhir --}}
                <div class="mb-3">
                    <label for="jenis_surat_akhir" class="form-label">Jenis Surat Akhir</label>
                    <select name="jenis_surat_akhir" id="jenis_surat_akhir" class="form-select" required>
                        @foreach ($masterSuratList as $surat)
                            <option value="{{ $surat->id }}"
                                {{ $masterPengajuan->master_surat->id == $surat->id ? 'selected' : '' }}>
                                {{ $surat->nama_surat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="minimum_semester" class="form-label">Minimum Semester Pengajuan</label>
                    <select name="minimum_semester" id="minimum_semester" class="form-select" required>
                        <option value="">-- Pilih Semester --</option>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}"
                                {{ old('minimum_semester', $masterPengajuan->minimum_semester) == $i ? 'selected' : '' }}>
                                Semester {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Buttons --}}
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('master-pengajuan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            let inputIndex = {{ count($masterPengajuan->pengajuan_pertanyaan) }};

            // Initialize Select2 for user dropdowns
            $('.user-select').select2();

            // Show/hide user selection based on checkbox
            $('.role-checkbox').change(function() {
                const role = $(this).data('role');
                if ($(this).is(':checked')) {
                    $(`#${role}_user_selection`).show();
                    $(`#by_${role}_user_id`).prop('required', true);
                } else {
                    $(`#${role}_user_selection`).hide();
                    $(`#by_${role}_user_id`).prop('required', false);
                }
            });

            // Add new input field dynamically
            $('#addInputPertanyaan').on('click', function() {
                $('#inputPertanyaanContainer').append(`
                    <div class="input-group mb-2">
                        <input type="text" name="input_pertanyaan[${inputIndex}][pertanyaan]" class="form-control" placeholder="Pertanyaan" required>
                        <select name="input_pertanyaan[${inputIndex}][jenis_input]" class="form-select" required>
                            <option value="text">Text</option>
                            <option value="file">File</option>
                        </select>
                        <button type="button" class="btn btn-danger remove-input">−</button>
                    </div>
                `);
                inputIndex++;
            });

            // Remove input field
            $(document).on('click', '.remove-input', function() {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
@endpush

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush
