<?php

namespace App\Http\Controllers;

use App\Models\Api\MasterPengajuan;
use App\Models\Api\NomorSurat;
use App\Models\Api\MasterSurat;
use App\Models\Api\Pengajuan;
use App\Models\Api\Verifikasi;
use App\Models\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function indexPengajuan()
    {
        try {
            $userService = new UserService();
            $profile = $userService->getMyProfile();
            $account = $profile->getData()->data->account;

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

            // Filter pengajuan by account id
            $pengajuanList = $pengajuanList->filter(function ($item) use ($account) {
                return $item->user->id === $account->id;
            });

            return view('user.pengajuan.index', [
                'pengajuanList' => $pengajuanList,
                'account' => $account
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load pengajuan: ' . $e->getMessage());
        }
    }


    public function showPengajuan($id)
    {
        try {
            $data = Pengajuan::getById($id);
            $verifikasi = Verifikasi::getAll();

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

            return view('user.pengajuan.detail', [
                'pengajuan' => $data,
                'verifikasi' => $verifikasiByPengajuan,
                'isVerified' => $isVerified
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());

            return redirect()->route('pengajuan.index');
        }
    }

    public function addPengajuanIndex()
    {
        try {
            $masterPengajuan = MasterPengajuan::getAll();

            // Tambahkan ini:
            $userService = new UserService();
            $profile = $userService->getMyProfile();
            $profile = $profile->getData()->data->profile;

            return view('user.pengajuan.addIndex', [
                'masterPengajuanList' => $masterPengajuan,
                'profile' => $profile // <-- kirim ke blade
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load master pengajuan: ' . $e->getMessage());
        }
    }


    public function addPengajuan($id)
    {
        try {
            $masterPengajuan = MasterPengajuan::getAll();
            $selectedMasterPengajuan = collect($masterPengajuan)->firstWhere('id', $id);

            if (!$selectedMasterPengajuan) {
                throw new \Exception('Master pengajuan not found');
            }

            return view('user.pengajuan.add', ['masterPengajuan' => $selectedMasterPengajuan]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load master pengajuan: ' . $e->getMessage());
        }
    }

    public function indexUser()
    {
        return view('user.index');
    }

    public function storePengajuan(Request $request)
    {
        try {
            $validated = $request->validate([
                'master_pengajuan_id' => 'required|integer',
                'jawaban' => 'required|array',
                'jawaban.*.pertanyaan_id' => 'required|integer',
                'jawaban.*.jawaban' => $request->hasFile('jawaban.*.jawaban') ? 'sometimes' : 'required|string',
            ]);

            // Process each answer
            $processedJawaban = [];
            foreach ($validated['jawaban'] as $index => $jawabanItem) {
                // Check if this is a file upload
                if ($request->hasFile("jawaban.$index.jawaban")) {
                    $file = $request->file("jawaban.$index.jawaban");

                    // Upload file to external endpoint
                    $uploadResponse = $this->uploadFileToExternalService($file);

                    if (!$uploadResponse['success']) {
                        throw new \Exception('File upload failed: ' . ($uploadResponse['message'] ?? 'Unknown error'));
                    }

                    // Store the URL from the response
                    $processedJawaban[] = [
                        'pertanyaan_id' => $jawabanItem['pertanyaan_id'],
                        'jawaban' => $uploadResponse['data']['url']
                    ];
                } else {
                    // Regular text answer
                    $processedJawaban[] = $jawabanItem;
                }
            }

            // Replace the original jawaban with processed data
            $validated['jawaban'] = $processedJawaban;

            $pengajuan = Pengajuan::create($validated);

            $masterPengajuan = MasterPengajuan::getAll();
            $masterPengajuan = collect($masterPengajuan)->firstWhere('id', $validated['master_pengajuan_id']);

            $trueIsFlags = collect($masterPengajuan)
                ->filter(function ($value, $key) {
                    return str_starts_with($key, 'is_') && $value === true;
                })
                ->keys()
                ->toArray();

            foreach ($trueIsFlags as $flag) {
                Verifikasi::create([
                    'pengajuan_id' => $pengajuan->id,
                    'user_id' => 1,
                    'komentar' => '',
                    'status' => 'menunggu',
                    'verified_at' => null,
                    'is_admin' => $flag == 'is_admin',
                    'is_prodi' => $flag == 'is_prodi',
                    'is_doswal' => $flag == 'is_doswal',
                    'is_dosen' => $flag == 'is_dosen',
                    'is_staff' => $flag == 'is_staff',
                    'is_wk' => $flag == 'is_wk',
                    'is_pimpinan' => $flag == 'is_pimpinan',
                    'is_dospem' => $flag == 'is_dospem',
                ]);
            }

            return redirect()->route('user.pengajuan.index')
                ->with('success', 'Pengajuan created successfully');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create pengajuan: ' . $e->getMessage());
        }
    }

    protected function uploadFileToExternalService($file)
    {
        $client = new \GuzzleHttp\Client();
        $apiUrl = config('myconfig.api.base_url') . 'surat/v2/storage/r2';
        $accessToken = Session::get('token');

        try {
            $response = $client->post($apiUrl, [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => fopen($file->getPathname(), 'r'),
                        'filename' => $file->getClientOriginalName()
                    ]
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);


            // Validate the response structure
            if (!isset($responseData['success']) || !isset($responseData['data'])) {
                throw new \Exception('Invalid API response format');
            }

            return $responseData;
        } catch (\Exception $e) {
            throw new \Exception("Failed to upload file: " . $e->getMessage());
        }
    }

    public function adminDashboard()
    {
        try {
            // Ambil data master & pengajuan
            $masterPengajuan = MasterPengajuan::getAll();
            $masterSurat = MasterSurat::getAll();
            $pengajuan = Pengajuan::getAll();

            // Ambil token dari session
            $token = session('token');

            // Ambil profil user langsung via endpoint /users/me
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

            $data = [
                'masterPengajuanCount' => count($masterPengajuan),
                'masterSuratCount' => count($masterSurat),
                'pengajuanCount' => count($pengajuan),
            ];
            // dd($profile);

            return view('dashboard', [
                'data' => $data,
                'profile' => $profile,
                'account' => $account,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat dashboard: ' . $e->getMessage());
        }
    }
}
