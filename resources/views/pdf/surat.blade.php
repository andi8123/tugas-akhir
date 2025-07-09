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
        }

        .kop .logo {
            position: absolute;
            left: 40px;
            margin-right: 0px;
        }

        .kop .text {
            display: inline-block;
            text-align: center;
            width: calc(100% - 200px);
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

    <!-- Isi Surat -->
    <div class="content">
        <h3 style="text-align: center; margin-bottom: 0;">{{ $pengajuan->master_pengajuan->master_surat->nama_surat }}
        </h3>
        <h5 style="text-align: center; margin-top: 8px;">Nomor:
            {{ $nomorSurat['nomor_surat'] ?? '0154/STMIK-BDG/e/IV/ 2025' }}</h5>
        @php
            $isiSurat = $pengajuan->master_pengajuan->master_surat->isi_surat ?? '';

            use Carbon\Carbon;

            setlocale(LC_TIME, 'id_ID'); // untuk format strftime (jika digunakan)
            Carbon::setLocale('id'); // untuk Carbon

            // First, replace special user-related placeholders
            $userPlaceholders = [
                'NIM' => $pengajuan->user->nim ?? '',
                'Nama' => $pengajuan->user->nama_mahasiswa ?? '',
                'Email' => $pengajuan->user->email ?? '',
                'Jurusan' => $pengajuan->user->jurusan ?? '',
                'Tanggal Lahir' => $pengajuan->user->tanggal_lahir ?? '',
                'Tanggal Cetak' => date('d M Y', strtotime($pengajuan->created_at ?? now())),
                'Prodi' => ucwords(strtolower(str_replace('S1 - ', '', $pengajuan->user->jurusan ?? ''))),
                'TTL' => ($pengajuan->user->tmp_lahir ?? '') . ', ' . Carbon::parse($pengajuan->user->tgl_lahir ?? '')->translatedFormat('d F Y'),
                // Add more special placeholders as needed
            ];

            foreach ($userPlaceholders as $placeholder => $value) {
                $isiSurat = str_replace('{' . $placeholder . '}', $value, $isiSurat);
            }

            // Then, replace remaining placeholders with answers from pengajuan_jawaban
            foreach ($pengajuan->pengajuan_jawaban as $jawaban) {
                $pertanyaan = $jawaban->pengajuan_pertanyaan->pertanyaan;
                $nilaiJawaban = $jawaban->jawaban;

                // Only replace if the placeholder still exists (wasn't replaced in the first pass)
    $isiSurat = str_replace('{' . $pertanyaan . '}', $nilaiJawaban, $isiSurat);
            }
        @endphp
        <div>
            {!! $isiSurat ?? '' !!}
        </div>
        {{-- <p><strong>Nama:</strong> </p>
        <p><strong>NIK:</strong></p>
        <p><strong>Jenis Surat:</strong> </p>
        <p><strong>Tanggal Pengajuan:</strong> </p>
        <br><br>
        <p>Dengan ini mengajukan permohonan sesuai jenis surat di atas untuk keperluan administrasi. Demikian surat ini
            dibuat untuk dapat diproses sebagaimana mestinya.</p>
        <br><br>
        <p>Bandung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p><strong>Hormat Saya,</strong></p>
        <br><br><br>
        <p><strong></strong></p> --}}
    </div>
</body>

</html>
