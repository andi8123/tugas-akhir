@extends('layouts.admin')

@section('title', 'Tambah Master Surat')

@section('content')
    <div class="container mt-4">
        <div class="card p-4">

            <form method="POST" action="{{ route('master-surat.store') }}">
                @csrf
                @method('POST')

                <div class="mb-3">
                    <label for="nama_surat" class="form-label">Nama Surat</label>
                    <input type="text" name="nama_surat" id="nama_surat" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="docx_input" class="form-label">Import dari File DOCX</label>
                    <input type="file" id="docx_input" class="form-control" accept=".docx">
                    {{-- <small class="text-muted">File hanya digunakan untuk mengisi CKEditor, tidak akan dikirim.</small> --}}
                </div>

                
                <div class="mb-3" style="display: none;">
                    <label for="isi_surat" class="form-label">Isi Surat</label>
                    <textarea name="isi_surat" id="isi_surat" class="form-control" rows="5"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('master-surat.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script src="https://unpkg.com/mammoth/mammoth.browser.min.js"></script>

    <script>
        // Inisialisasi CKEditor
        CKEDITOR.replace('isi_surat');

        // Fungsi saat file DOCX dipilih
        document.getElementById('docx_input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                const arrayBuffer = event.target.result;

                mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
                    .then(function(result) {
                        let html = result.value;

                        // Tambahkan border & padding agar tabel tampil rapi
                        html = html.replace(/<table>/g, '<table style="border-collapse: collapse; width: 100%;" border="1">');
                        html = html.replace(/<td>/g, '<td style="border: 1px solid #000; padding: 5px;">');
                        html = html.replace(/<th>/g, '<th style="border: 1px solid #000; padding: 5px; background-color: #f9f9f9;">');

                        // Set hasil ke CKEditor
                        CKEDITOR.instances['isi_surat'].setData(html);
                    })
                    .catch(function(err) {
                        alert("Gagal membaca file DOCX: " + err.message);
                    });
            };

            reader.readAsArrayBuffer(file);
        });
    </script>
@endpush

