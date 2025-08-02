<?php

namespace App\Http\Controllers;

use App\Models\Api\MasterPengajuan;
use App\Models\Api\NomorSurat;
use App\Models\Api\Pengajuan;
use App\Models\Api\Verifikasi;
use App\Models\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    public function index()
    {
        try {
            // Ambil semua data pengajuan
            $pengajuanList = Pengajuan::getAll();

            // Ambil data verifikasi dari API eksternal
            $externalVerifikasi = Http::get('https://680ba389d5075a76d98be950.mockapi.io/api/verifikasi');

            if ($externalVerifikasi->successful()) {
                $verifikasiData = $externalVerifikasi->json();
            } else {
                $verifikasiData = [];
            }

            // Tambahkan status verifikasi ke setiap pengajuan
            $pengajuanList = collect($pengajuanList)->map(function ($item) use ($verifikasiData) {
                $verifikasiByPengajuan = collect($verifikasiData)->filter(function ($verif) use ($item) {
                    return $verif['pengajuan_id'] == $item->id;
                });

                // Default status
                $statusVerifikasi = 'proses';

                if ($verifikasiByPengajuan->count() > 0) {
                    if ($verifikasiByPengajuan->contains('status', 'ditolak')) {
                        $statusVerifikasi = 'ditolak';
                    } elseif (
                        $verifikasiByPengajuan->filter(function ($v) {
                            return $v['status'] == 'diterima';
                        })->count() === $verifikasiByPengajuan->count()
                    ) {
                        $statusVerifikasi = 'diterima';
                    }
                }

                $item->status_verifikasi = $statusVerifikasi;
                return $item;
            });

            $current_role = array_keys(Session::get('role'));

            // Cek apakah role adalah admin
            if (in_array('is_admin', $current_role)) {
                // Admin bisa melihat semua pengajuan tanpa filter
                $pengajuanList_filtered = $pengajuanList;
            } else {
                // Selain admin, filter berdasarkan role yang aktif
                $pengajuanList_filtered = $pengajuanList->filter(function ($item) use ($current_role) {
                    return $item->master_pengajuan->{$current_role[0]} == true;
                });
            }

            return view('pengajuan.index', ['pengajuanList' => $pengajuanList_filtered]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load pengajuan: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        try {
            $data = Pengajuan::getById($id);
            $verifikasi = Verifikasi::getAll();

            // Fetch external API data
            $nomorSuratRes = Http::get('https://680ba389d5075a76d98be950.mockapi.io/api/nomorsurat');

            if ($nomorSuratRes->successful()) {
                $nomorSurat = $nomorSuratRes->json();
            } else {
                $nomorSurat = [];
            }

            $nomorSurat = collect($nomorSurat)->firstWhere('pengajuan_id', $id);

            // Fetch external API data
            $externalVerifikasi = Http::get('https://680ba389d5075a76d98be950.mockapi.io/api/verifikasi');

            if ($externalVerifikasi->successful()) {
                $verifikasiData = $externalVerifikasi->json();
            } else {
                $verifikasiData = [];
            }

            // get all verifikasi by pengajuan id
            $verifikasiByPengajuan = collect($verifikasiData)->filter(function ($item) use ($id) {
                return $item['pengajuan_id'] == $id;
            });

            // loop verifikasi pengajuan and check if status is disetujui or not
            $isVerified = collect($verifikasiByPengajuan)->filter(function ($item) {
                return $item['status'] == 'diterima';
            })->count() === $verifikasiByPengajuan->count();

            // dd($verifikasiByPengajuan);

            return view('pengajuan.detail', [
                'pengajuan' => $data,
                'verifikasi' => $verifikasiByPengajuan,
                'nomorSurat' => $nomorSurat,
                'isVerified' => $isVerified
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('data-pengajuan.index');
        }
    }

    public function add()
    {
        try {
            $masterPengajuan = MasterPengajuan::getAll();
            return view('pengajuan.add', ['masterPengajuanList' => $masterPengajuan]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load create form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'master_pengajuan_id' => 'required|integer',
                'jawaban' => 'required|array',
                'jawaban.*.pertanyaan_id' => 'required|integer',
                'jawaban.*.jawaban' => 'required|string',
            ]);

            $pengajuanRes = Pengajuan::create($validated);

            return redirect()->route('data-pengajuan.index')
                ->with('success', 'Pengajuan created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create pengajuan: ' . $e->getMessage());
        }
    }

    public function addPengajuan()
    {
        try {
            $masterPengajuan = MasterPengajuan::getAll();
            return view('user.pengajuan.add', ['masterPengajuanList' => $masterPengajuan]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load create form: ' . $e->getMessage());
        }
    }

    public function indexUser()
    {
        try {
            // Ambil token untuk profil
            $token = session('token');

            $response = Http::withToken($token)
                ->accept('application/json')
                ->get(config('myconfig.api.base_url') . 'users/me');

            if ($response->successful()) {
                $responseData = $response->json();
                $profile = $responseData['data']['profile'] ?? null;
                $account = $responseData['data']['account'] ?? null;
            } else {
                $profile = null;
                $account = null;
            }

            // Ambil semua pengajuan
            $pengajuanList = Pengajuan::getAll();

            // Ambil verifikasi dari API eksternal
            $verifikasi = Http::get('https://680ba389d5075a76d98be950.mockapi.io/api/verifikasi');
            $verifikasiData = $verifikasi->successful() ? $verifikasi->json() : [];

            // Hitung status
            $pengajuanDenganStatus = collect($pengajuanList)->map(function ($item) use ($verifikasiData) {
                $verifikasiById = collect($verifikasiData)->where('pengajuan_id', $item->id);

                $status = 'proses';
                if ($verifikasiById->count()) {
                    if ($verifikasiById->contains('status', 'ditolak')) {
                        $status = 'ditolak';
                    } elseif ($verifikasiById->every(fn($v) => $v['status'] === 'diterima')) {
                        $status = 'diterima';
                    }
                }

                $item->status_verifikasi = $status;
                return $item;
            });

            // Hitung total berdasarkan status
            $data = [
                'pengajuanCount'     => $pengajuanDenganStatus->count(),
                'pengajuanDiterima'  => $pengajuanDenganStatus->where('status_verifikasi', 'diterima')->count(),
                'pengajuanMenunggu'  => $pengajuanDenganStatus->where('status_verifikasi', 'proses')->count(),
                'pengajuanDitolak'   => $pengajuanDenganStatus->where('status_verifikasi', 'ditolak')->count(),
            ];

            return view('user.index', [
                'profile' => $profile,
                'account' => $account,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat halaman user: ' . $e->getMessage());
        }
    }



    public function edit($id)
    {
        try {
            $data = Pengajuan::getById($id);
            $masterPengajuan = MasterPengajuan::getAll();

            return view('pengajuan.edit', [
                'pengajuan' => $data,
                'masterPengajuanList' => $masterPengajuan
            ]);
        } catch (\Exception $e) {
            return redirect()->route('data-pengajuan.index')
                ->with('error', 'Failed to edit pengajuan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $roles = [
                'admin',
                'prodi',
                'doswal',
                'dosen',
                'staff',
                'wk',
                'pimpinan',
                'dospem',
                'marketing',
                'akademik',
                'baak',
                'secretary',
                'bendahara',
                'kemahasiswaan'
            ];

            foreach ($roles as $role) {
                $isKey = "is_{$role}";
                $byKey = "by_is_{$role}_user_id";

                if (!$request->has($isKey)) {
                    $request->request->remove($byKey);
                }
            }

            $validated = $request->validate([
                'nama_pengajuan' => 'required|string',
                'input_pertanyaan' => 'required|array',
                'input_pertanyaan.*.pertanyaan' => 'required|string',
                'input_pertanyaan.*.jenis_input' => 'required|string|in:text,file',
                'is_admin' => 'sometimes|boolean',
                'by_is_admin_user_id' => 'required_if:is_admin,true|integer|nullable',
                'is_prodi' => 'sometimes|boolean',
                'by_is_prodi_user_id' => 'required_if:is_prodi,true|integer|nullable',
                'is_doswal' => 'sometimes|boolean',
                'by_is_doswal_user_id' => 'required_if:is_doswal,true|integer|nullable',
                'is_dosen' => 'sometimes|boolean',
                'by_is_dosen_user_id' => 'required_if:is_dosen,true|integer|nullable',
                'is_staff' => 'sometimes|boolean',
                'by_is_staff_user_id' => 'required_if:is_staff,true|integer|nullable',
                'is_wk' => 'sometimes|boolean',
                'by_is_wk_user_id' => 'required_if:is_wk,true|integer|nullable',
                'is_pimpinan' => 'sometimes|boolean',
                'by_is_pimpinan_user_id' => 'required_if:is_pimpinan,true|integer|nullable',
                'is_dospem' => 'sometimes|boolean',
                'by_is_dospem_user_id' => 'required_if:is_dospem,true|integer|nullable',
                'is_marketing' => 'sometimes|boolean',
                'by_is_marketing_user_id' => 'required_if:is_marketing,true|integer|nullable',
                'is_akademik' => 'sometimes|boolean',
                'by_is_akademik_user_id' => 'required_if:is_akademik,true|integer|nullable',
                'is_baak' => 'sometimes|boolean',
                'by_is_baak_user_id' => 'required_if:is_baak,true|integer|nullable',
                'is_secretary' => 'sometimes|boolean',
                'by_is_secretary_user_id' => 'required_if:is_secretary,true|integer|nullable',
                'is_bendahara' => 'sometimes|boolean',
                'by_is_bendahara_user_id' => 'required_if:is_bendahara,true|integer|nullable',
                'is_kemahasiswaan' => 'sometimes|boolean',
                'by_is_kemahasiswaan_user_id' => 'required_if:is_kemahasiswaan,true|integer|nullable',
                'jenis_surat_akhir' => 'required|integer',
            ]);

            $response = MasterPengajuan::update($id, $validated);
            return redirect()->route('master-pengajuan.index')
                ->with('success', 'Master pengajuan updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update master pengajuan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // 1. Hapus verifikasi (dari API eksternal)
            $verifikasi = Http::get('https://680ba389d5075a76d98be950.mockapi.io/api/verifikasi');
            $verifikasiData = $verifikasi->successful() ? $verifikasi->object() : [];
            collect($verifikasiData)->filter(fn($item) => $item->pengajuan_id == $id)
                ->each(fn($v) => Http::delete("https://680ba389d5075a76d98be950.mockapi.io/api/verifikasi/{$v->id}"));

            // 2. Hapus nomor surat
            $nomorSurat = Http::get('https://680ba389d5075a76d98be950.mockapi.io/api/nomorsurat');
            $nomorSuratData = $nomorSurat->successful() ? $nomorSurat->object() : [];
            collect($nomorSuratData)->filter(fn($item) => $item->pengajuan_id == $id)
                ->each(fn($ns) => Http::delete("https://680ba389d5075a76d98be950.mockapi.io/api/nomorsurat/{$ns->id}"));

            // 4. Hapus pengajuan (via API)
            $response = Pengajuan::delete($id);

            return redirect()->back()->with('success', 'Pengajuan deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete pengajuan: ' . $e->getMessage());
        }
    }


    public function verifikasiForm($id)
    {
        try {
            $data = Pengajuan::getById($id);
            return view('pengajuan.verifikasi', ['pengajuan' => $data]);
        } catch (\Exception $e) {
            return redirect()->route('data-pengajuan.index')
                ->with('error', 'Failed to load verification form: ' . $e->getMessage());
        }
    }

    // public function verifikasi(Request $request, $id)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'status' => 'required|boolean',
    //             'role' => 'required|string',
    //             'komentar' => 'nullable|string',
    //         ]);

    //         $response = Pengajuan::verifikasi($id, $validated);
    //         return redirect()->route('pengajuan.show', $id)
    //             ->with('success', 'Pengajuan verified successfully');
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->withInput()
    //             ->with('error', 'Failed to verify pengajuan: ' . $e->getMessage());
    //     }
    // }

    public function verifikasi(Request $request, $id)
    {
        try {
            // Validasi input dari frontend
            $validated = $request->validate([
                'status' => 'required|string', // contoh: "diterima" atau "ditolak"
                'role' => 'required|string', // contoh: 'is_admin', 'is_mhs', dll.
                'komentar' => 'nullable|string',
                'value' => 'required|string',  // 'true' atau 'false'
            ]);

            // dd($validated);

            $externalVerifikasi = Http::get('https://680ba389d5075a76d98be950.mockapi.io/api/verifikasi');
            $verifikasiData = $externalVerifikasi->successful() ? $externalVerifikasi->json() : [];

            $role = $validated['role'];
            $value = $validated['value'];

            $verifikasiByPengajuan = collect($verifikasiData)->filter(function ($item) use ($id, $role) {
                return isset($item['pengajuan_id'], $item[$role])
                    && $item['pengajuan_id'] == $id
                    && $item[$role] == true;
            });

            if ($verifikasiByPengajuan->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ditemukan data verifikasi untuk pengajuan dan role tersebut.');
            }

            // Tentukan status berdasarkan value
            $newStatus = ($value === 'true') ? 'diterima' : 'ditolak';

            foreach ($verifikasiByPengajuan as $item) {
                $verifikasiId = $item['id'];

                Http::put("https://680ba389d5075a76d98be950.mockapi.io/api/verifikasi/{$verifikasiId}", [
                    'verifikasi' => $value === 'true',
                    'status' => $newStatus,
                    'verified_at' => now()->toIso8601String(),
                    'komentar' => $validated['komentar'] ?? null,
                ]);
            }

            return redirect()->back()->with('success', 'Pengajuan berhasil diverifikasi dengan status: ' . $newStatus);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memverifikasi pengajuan: ' . $e->getMessage());
        }
    }


    public function generatePDF($pengajuanId)
    {
        try {
            $pengajuan = Pengajuan::getById($pengajuanId);
            $masterPengajuan = MasterPengajuan::getAll();
            $masterPengajuan = collect($masterPengajuan)->firstWhere('id', $pengajuan->master_pengajuan_id);

            if (!$pengajuan) {
                throw new \Exception('Pengajuan not found');
            }

            // Fetch external API data
            $nomorSuratRes = Http::get('https://680ba389d5075a76d98be950.mockapi.io/api/nomorsurat');

            if ($nomorSuratRes->successful()) {
                $nomorSurat = $nomorSuratRes->json();
            } else {
                $nomorSurat = [];
            }

            $nomorSurat = collect($nomorSurat)->firstWhere('pengajuan_id', $pengajuanId);
            // dd($pengajuan);

            $pdf = Pdf::loadView('pdf.surat', compact('pengajuan', 'nomorSurat'));
            return $pdf->download('surat-' . $pengajuan->id . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Failed to load pengajuan: ' . $e->getMessage());
        }
    }

    public function addNomorSurat(Request $request, $id)
    {
        try {
            $pengajuan = Pengajuan::getById($id);
            $nomorSurat = NomorSurat::getAll();
            $nomorSurat = collect($nomorSurat)->firstWhere('pengajuan_id', $id);

            if (!$nomorSurat) {
                $nomorSurat = NomorSurat::create(
                    [
                        'pengajuan_id' => $pengajuan->id,
                        'nomor_surat' => $request->input('nomor_surat'),
                    ]
                );
            }

            return back()->with('success', 'Nomor surat berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add nomor surat: ' . $e->getMessage());
        }
    }



    public function cetakPDF(Request $request)
    {
        // Ambil semua data dari API
        $data = collect(Pengajuan::getAll());

        // Ambil verifikasi dari API eksternal
        $verifikasi = Http::get('https://680ba389d5075a76d98be950.mockapi.io/api/verifikasi');
        $verifikasiData = $verifikasi->successful() ? $verifikasi->json() : [];

        // Tambahkan status verifikasi ke setiap pengajuan
        $data = $data->map(function ($item) use ($verifikasiData) {
            $verifikasiById = collect($verifikasiData)->where('pengajuan_id', $item->id);

            $status = 'proses';

            if ($verifikasiById->count() > 0) {
                // Normalisasi semua status ke lowercase
                $statuses = $verifikasiById->pluck('status')->map(fn($s) => strtolower($s));

                if ($statuses->contains('ditolak')) {
                    $status = 'ditolak';
                } elseif ($statuses->every(fn($s) => $s === 'diterima')) {
                    $status = 'diterima';
                }
            }

            $item->status_verifikasi = $status;
            return $item;
        });

        // Filter jika ada jenis pengajuan
        if ($request->jenis_pengajuan && $request->jenis_pengajuan !== 'all') {
            $data = $data->where('master_pengajuan.id', $request->jenis_pengajuan);
        }

        // Filter berdasarkan tanggal dibuat
        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $data = $data->filter(function ($item) use ($request) {
                $created = \Carbon\Carbon::parse($item->created_at)->format('Y-m-d');
                return $created >= $request->tanggal_awal && $created <= $request->tanggal_akhir;
            });
        }

        // ✅ Filter berdasarkan status verifikasi (ditambahkan di sini)
        if ($request->status_verifikasi && $request->status_verifikasi !== 'all') {
            $data = $data->where('status_verifikasi', $request->status_verifikasi);
        }

        // Cetak PDF
        $pdf = Pdf::loadView('pdf.rekap_pdf', compact('data'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Rekapitulasi-Pengajuan.pdf');
    }
}
