@extends('layouts.admin')

@section('title', 'Edit Master Surat')

@section('content')
    <div class="container mt-4">
        <div class="card">

            <form method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_surat" class="form-label">Nama Surat</label>
                    <input type="text" name="nama_surat" id="nama_surat" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="isi_surat" class="form-label">Isi Surat</label>
                    <textarea name="isi_surat" id="isi_surat" class="form-control" rows="5"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('master-surat.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('isi_surat');
    </script>
@endsection
