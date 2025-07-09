<!-- resources/views/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Master Surat')

@section('content')
    <div class="">
        <div class="card mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <input id="searchInput" type="text" class="form-control w-25" placeholder="Cari Surat">
                <a class="btn btn-primary btn-sm" href="{{ route('master-surat.add') }}">
                    <i data-feather="plus" class="text-white" style="width: 20px; height: 20px;"></i>
                    Tambah Master Surat</a>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table" style="border-radius: 12px" id="masterTable" <thead class="table-light">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Surat</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($masterSuratList as $key => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_surat }}</td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('master-surat.edit', $item->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i data-feather="edit" class="text-white"
                                                style="width: 20px; height: 20px;"></i>
                                        </a>
                                        <form action="{{ route('master-surat.destroy', ['id' => $item->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="delete-btn btn btn-danger btn-sm">
                                                <i data-feather="trash" class="text-white"
                                                    style="width: 20px; height: 20px;"></i>
                                            </button>
                                        </form>
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
                    "targets": [2]
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
