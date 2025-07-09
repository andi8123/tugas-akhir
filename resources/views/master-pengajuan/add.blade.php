@extends('layouts.admin')

@section('title', 'Tambah Master Pengajuan')

@section('content')
    <div class="container mt-4">
        <div class="card p-4">
            <h5 class="card-title">Tambah Master Pengajuan</h5>
            <form method="POST" action="{{ route('master-pengajuan.store') }}">
                @csrf

                {{-- Nama Pengajuan --}}
                <div class="mb-3">
                    <label for="nama_pengajuan" class="form-label">Nama Pengajuan</label>
                    <input type="text" name="nama_pengajuan" id="nama_pengajuan" class="form-control" required>
                </div>

                {{-- Input Pertanyaan (Dynamic Fields) --}}
                <div class="mb-3">
                    <label class="form-label">Input Pertanyaan</label>
                    <div id="inputPertanyaanContainer">
                        <div class="input-group mb-2">
                            <input type="text" name="input_pertanyaan[0][pertanyaan]" class="form-control"
                                placeholder="Pertanyaan" required>
                            <select name="input_pertanyaan[0][jenis_input]" class="form-select" required>
                                <option value="text">Text</option>
                                <option value="file">File</option>
                            </select>
                            <button type="button" class="btn btn-danger remove-input" disabled>−</button>
                        </div>
                    </div>
                    <button type="button" id="addInputPertanyaan" class="btn btn-success btn-sm">+ Tambah
                        Pertanyaan</button>
                </div>

                {{-- Yang Meng-ACC --}}
                <div class="mb-3">
                    <label class="form-label">Yang Meng-ACC</label>

                    @foreach ($roleLabels as $role => $label)
                        <div class="mb-2">
                            <div class="form-check">
                                <input class="form-check-input role-checkbox" type="checkbox" name="{{ $role }}"
                                    id="{{ $role }}" value="1" data-role="{{ $role }}">
                                <label class="form-check-label" for="{{ $role }}">{{ $label }}</label>
                            </div>
                            <div class="user-selection mt-2" id="{{ $role }}_user_selection" style="display: none;">
                                <label for="by_{{ $role }}_user_id" class="form-label">Pilih User
                                    {{ $label }}</label>
                                <select name="by_{{ $role }}_user_id" id="by_{{ $role }}_user_id"
                                    class="form-select user-select">
                                    @foreach ($usersByRole[$role] as $user)
                                        <option value="{{ $user->id }}">{{ $user->kd_user }} - {{ $user->email }}
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
                            <option value="{{ $surat->id }}">{{ $surat->nama_surat }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <button type="submit" class="btn btn-primary">Simpan</button>
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
            let inputIndex = 1;

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
