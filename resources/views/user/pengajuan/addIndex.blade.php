@extends('layouts.admin', [
    'role' => 'user',
])

@section('title', 'Buat Pengajuan')

@section('content')
    <div class="">
        <div class="card p-3">
            <div class="table-responsive">
                <table class="table" id="masterTable">
                    <thead class="table-light mt-3">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Pengajuan</th>
                            <th>Jumlah Input</th>
                            <th>Jenis Surat Akhir</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($masterPengajuanList as $key => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_pengajuan ?? '-' }}</td>
                                <td>{{ count($item->pengajuan_pertanyaan) ?? 0 }}</td>
                                <td>{{ $item->master_surat->nama_surat ?? '-' }}</td>

                                @php
                                    $currentYear = date('Y');
                                    $currentMonth = date('n'); // 1–12
                                    $masukTahun = $profile->masuk_tahun;

                                    $semester = ($currentYear - $masukTahun) * 2;
                                    if ($currentMonth >= 8) {
                                        $semester += 1;
                                    }

                                    // Maksimal semester 8
                                    $semester = min($semester, 8);
                                @endphp

                                <td>
                                    <div class="d-flex gap-2">
                                        @if ($semester >= $item->minimum_semester)
                                            <a href="{{ route('data-pengajuan.tambah', ['id' => $item->id]) }}"
                                                class="btn btn-warning btn-sm">
                                                Buat Pengajuan
                                            </a>
                                        @else
                                            <span class="text-danger small fst-italic">
                                                Minimal Semester {{ $item->minimum_semester }} untuk mengajukan
                                            </span>
                                        @endif
                                    </div>
                                </td>


                            </tr>
                        @endforeach

                        @if (empty($masterPengajuanList))
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data tersedia</td>
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
                    "targets": [4]
                }]
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
