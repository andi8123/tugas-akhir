<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Pengajuan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 40px;
        }

        .kop {
            text-align: center;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }

        .kop .logo {
            position: absolute;
            left: 0;
            top: 0;
        }

        .kop .text {
            text-align: center;
            margin-left: 100px;
            margin-right: 100px;
        }

        .kop .text h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .kop .text h2 {
            margin: 0;
            font-size: 14px;
            font-weight: normal;
        }

        .kop .text p {
            margin: 2px 0;
            font-size: 10px;
        }

        .content {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: center;
        }

        td {
            padding: 6px;
            text-align: center;
        }

        .no-data {
            text-align: center;
            font-style: italic;
        }
    </style>
</head>

<body>
    <!-- Kop Surat -->
    <div class="kop">
        <div class="logo">
            <img style="height: 70px; width: 70px;" src="{{ public_path('/images/logo.png') }}" alt="Logo STMIK Bandung">
        </div>
        <div class="text">
            <h1>STMIK BANDUNG</h1>
            <h2>SEKOLAH TINGGI MANAJEMEN INFORMATIKA DAN KOMPUTER BANDUNG</h2>
            <p>Jl. Cikutra No. 113 Bandung, Telp +62 22 7207777, www.stmik-bandung.ac.id</p>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Pengajuan</th>
                    <th>Email Pengaju</th>
                    <th>Tanggal Dibuat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->master_pengajuan->master_surat->nama_surat ?? '-' }}</td>
                        <td>{{ $item->user->email ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                        <td>
                            @if ($item->status_verifikasi == 'diterima')
                                Diterima
                            @elseif ($item->status_verifikasi == 'ditolak')
                                Ditolak
                            @else
                                Proses
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="no-data">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
