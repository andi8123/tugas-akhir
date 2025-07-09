<!-- resources/views/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Master Role')

{{-- DUMMY DATA --}}
@php
    $surat = [
        (object) ['id' => 1, 'nama' => 'Administrasi'],
        (object) ['id' => 2, 'nama' => 'Dosen'],
        (object) ['id' => 3, 'nama' => 'Kepala Prodi'],
    ];
@endphp

@section('content')
    <div class="">
        <div class="card mb-3">
            <form class="d-flex gap-3 align-items-center">
                <input type="text" class="form-control w-25" placeholder="Tulis nama role">
                <button class="btn btn-primary btn-sm" type="submit">
                    <i data-feather="plus" class="text-white" style="width: 20px; height: 20px;"></i>
                    Tambah Role</button>
            </form>
        </div>

        <div class="card">
            <input type="text" class="form-control w-25 mb-4" placeholder="Cari Role">

            <div class="table-responsive">
                <table class="table" style="border-radius: 12px">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Role</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($surat as $key => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('master-role.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                            <i data-feather="edit" class="text-white"
                                                style="width: 20px; height: 20px;"></i>
                                        </a>
                                        <a href="{{ route('master-role.edit', $item->id) }}" class="btn btn-danger btn-sm">
                                            <i data-feather="trash" class="text-white"
                                                style="width: 20px; height: 20px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
