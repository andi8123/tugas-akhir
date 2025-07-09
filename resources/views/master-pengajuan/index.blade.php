@extends('layouts.admin')

@section('title', 'Master Pengajuan')

@section('content')
    <div class="">
        <div class="card mb-3">
            <div class="d-flex justify-content-between align-items-center p-3">
                <input type="text" id="searchInput" class="form-control w-25" placeholder="Cari Pengajuan">
                <a class="btn btn-primary btn-sm" href="{{ route('master-pengajuan.add') }}">
                    <i data-feather="plus" class="text-white" style="width: 20px; height: 20px;"></i>
                    Tambah Master Pengajuan
                </a>
            </div>
        </div>

        <div class="card p-3">
            <div class="table-responsive">
                <table class="table" id="masterPengajuanTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Pengajuan</th>
                            <th>Jumlah Input</th>
                            <th>Role Acc</th>
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
                                <td>{{ App\Helpers\RoleHelper::toString($item) ?? '-' }}</td>
                                <td>{{ $item->master_surat->nama_surat ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('master-pengajuan.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i data-feather="edit" class="text-white"
                                                style="width: 20px; height: 20px;"></i>
                                        </a>
                                        <form method="POST"
                                            action="{{ route('master-pengajuan.delete', ['id' => $item->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="delete-btn btn-delete btn btn-danger btn-sm">
                                                <i data-feather="trash" class="text-white"
                                                    style="width: 20px; height: 20px;"></i>
                                            </button>
                                        </form>
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
            var table = $('#masterPengajuanTable').DataTable({
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
                "columnDefs": [
                    { "orderable": false, "targets": [5] } 
                ]
            });

            // Custom search input
            $('#searchInput').keyup(function(){
                table.search($(this).val()).draw();
            });

            // Add numbering to the first column
            table.on('order.dt search.dt', function () {
                table.column(0, {search:'applied', order:'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endpush