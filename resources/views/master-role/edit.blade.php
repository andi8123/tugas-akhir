@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
    <div class="container mt-4">
        <div class="card">

            <form method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_surat" class="form-label">Nama Role</label>
                    <input type="text" name="nama_surat" id="nama_surat" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('master-role.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
@endsection
