@extends('layouts.admin', [
    'role' => 'user',
])

@section('title', 'Data Pengajuan')

{{-- DUMMY DATA --}}
@php
    $data = [
        (object) [
            'id' => 1,
            'nama_surat' => 'Surat Keputusan',
            'email_pengaju' => 'user1@example.com',
            'tanggal_dibuat' => '2024-08-25',
            'status' => 'Menunggu Persetujuan',
        ],
        (object) [
            'id' => 2,
            'nama_surat' => 'Surat Tugas',
            'email_pengaju' => 'user2@example.com',
            'tanggal_dibuat' => '2024-08-24',
            'status' => 'Disetujui',
        ],
        (object) [
            'id' => 3,
            'nama_surat' => 'Surat Cuti',
            'email_pengaju' => 'user3@example.com',
            'tanggal_dibuat' => '2024-08-23',
            'status' => 'Ditolak',
        ],
    ];
@endphp

@section('content')
    <div class="container mt-4">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <input id="searchInput" type="text" class="form-control w-25" placeholder="Cari Pengajuan">
            </div>

            <div class="table-responsive">
                <table class="table" id="masterTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Jenis Pengajuan</th>
                            <th>Email Pengaju</th>
                            <th>Tanggal Dibuat</th>
                            <th>Status</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuanList as $key => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->master_pengajuan->master_surat->nama_surat }}</td>
                                <td>{{ $item->user->email }}</td>
                                <td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
                                <td>
                                    @if ($item->status_verifikasi == 'diterima')
                                        <span class="badge bg-success">Diterima</span>
                                    @elseif ($item->status_verifikasi == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Proses</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('user.pengajuan.show', $item->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i data-feather="more-vertical" class="text-white"
                                                style="width: 18px; height: 18px;"></i>
                                        </a>
                                        <form method="POST" action="{{ route('data-pengajuan.delete', $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm delete-btn btn-delete">
                                                <i data-feather="trash" class="text-white"
                                                    style="width: 18px; height: 18px;"></i>
                                            </button>
                                        </form>
                                    </div>
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

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#masterTable').DataTable({
                "dom": 'lrtip',
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [5]
                }]
            });

            // Custom search input
            $('#searchInput').keyup(function() {
                table.search($(this).val()).draw();
            });

            // Add numbering to the first column
            table.on('order.dt search.dt', function() {
                table.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endpush
