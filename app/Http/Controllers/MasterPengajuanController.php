<?php

namespace App\Http\Controllers;

use App\Models\Api\MasterPengajuan;
use App\Models\Api\MasterSurat;
use App\Models\Api\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MasterPengajuanController extends Controller
{
    public function index()
    {
        try {
            $masterPengajuan = MasterPengajuan::getAll();
            return view('master-pengajuan.index', ['masterPengajuanList' => $masterPengajuan]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load master pengajuan: ' . $e->getMessage());
        }
    }

    public function add()
    {
        try {
            $masterSurat = MasterSurat::getAll();
            $users = collect(User::getAll()->users)->filter(fn($item) => $item->is_mhs === false);

            $roleLabels = [
                'is_admin' => 'Admin',
                'is_prodi' => 'Prodi',
                'is_doswal' => 'Dosen Wali',
                'is_dosen' => 'Dosen',
                'is_staff' => 'Staff',
                'is_wk' => 'Wakil Ketua',
                // 'is_pimpinan' => 'Pimpinan',
                'is_dospem' => 'Dosen Pembimbing',
                // 'is_marketing' => 'Marketing',
                // 'is_akademik' => 'Akademik',
                // 'is_baak' => 'BAAK',
                // 'is_secretary' => 'Sekretaris',
                // 'is_bendahara' => 'Bendahara',
                // 'is_kemahasiswaan' => 'Kemahasiswaan',
            ];

            $usersByRole = [];

            foreach ($roleLabels as $role => $label) {
                $usersByRole[$role] = $users->filter(fn($user) => $user->$role === true);
            }

            return view('master-pengajuan.add', [
                'masterSuratList' => $masterSurat,
                'usersByRole' => $usersByRole,
                'roleLabels' => $roleLabels,
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Failed to load create form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_pengajuan' => 'required|string',
                'input_pertanyaan' => 'required|array',
                'input_pertanyaan.*.pertanyaan' => 'required|string',
                'input_pertanyaan.*.jenis_input' => 'required|string|in:text,file',
                'is_admin' => 'sometimes|boolean',
                'by_is_admin_user_id' => 'integer|nullable',
                'is_prodi' => 'sometimes|boolean',
                'by_is_prodi_user_id' => 'integer|nullable',
                'is_doswal' => 'sometimes|boolean',
                'is_dosen' => 'sometimes|boolean',
                'is_staff' => 'sometimes|boolean',
                'is_wk' => 'sometimes|boolean',
                'is_pimpinan' => 'sometimes|boolean',
                'is_dospem' => 'sometimes|boolean',
                'by_is_doswal_user_id' => 'integer|nullable',
                'by_is_dosen_user_id' => 'integer|nullable',
                'by_is_staff_user_id' => 'integer|nullable',
                'by_is_wk_user_id' => 'integer|nullable',
                'by_is_pimpinan_user_id' => 'integer|nullable',
                'by_is_dospem_user_id' => 'integer|nullable',
                'jenis_surat_akhir' => 'required|integer',
                'minimum_semester' => 'required|integer|min:1|max:8',
            ]);
            // dd($validated);

            MasterPengajuan::create($validated);

            return redirect()->route('master-pengajuan.index')
                ->with('success', 'Master pengajuan created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create master pengajuan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $allData = MasterPengajuan::getAll();
            $masterPengajuan = collect($allData)->firstWhere('id', $id);

            if (!$masterPengajuan) {
                throw new \Exception('Master pengajuan not found');
            }

            $masterSurat = MasterSurat::getAll();

            $users = collect(User::getAll()->users)->filter(fn($item) => $item->is_mhs === false);

            $roleLabels = [
                'is_admin' => 'Admin',
                'is_prodi' => 'Prodi',
                'is_doswal' => 'Dosen Wali',
                'is_dosen' => 'Dosen',
                'is_staff' => 'Staff',
                'is_wk' => 'Wakil Ketua',
                // 'is_pimpinan' => 'Pimpinan',
                'is_dospem' => 'Dosen Pembimbing',
                'is_marketing' => 'Marketing',
                'is_akademik' => 'Akademik',
                'is_baak' => 'BAAK',
                'is_secretary' => 'Sekretaris',
                'is_bendahara' => 'Bendahara',
                'is_kemahasiswaan' => 'Kemahasiswaan',
            ];

            $usersByRole = [];

            foreach ($roleLabels as $role => $label) {
                $usersByRole[$role] = $users->filter(fn($user) => property_exists($user, $role) && $user->$role === true);
            }

            return view('master-pengajuan.edit', [
                'masterPengajuan' => $masterPengajuan,
                'masterSuratList' => $masterSurat,
                'users' => $users,
                'usersByRole' => $usersByRole,
                'roleLabels' => $roleLabels,
            ]);            
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('master-pengajuan.index')
                ->with('error', 'Failed to edit master pengajuan: ' . $e->getMessage());
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

                if ($request->has($isKey)) {
                    $request->merge([
                        $isKey => filter_var($request->input($isKey), FILTER_VALIDATE_BOOLEAN),
                    ]);
                } else {
                    $request->merge([
                        $isKey => false,
                    ]);
                    $request->request->remove($byKey);
                }
            }
            
            // dd([
            //     'request_all' => $request->all(),
            //     'input_roles' => collect($roles)->mapWithKeys(function ($role) use ($request) {
            //         return [
            //             "is_$role" => $request->input("is_$role"),
            //             "by_is_{$role}_user_id" => $request->input("by_is_{$role}_user_id")
            //         ];
            //     }),
            // ]);
            

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
                'minimum_semester' => 'required|integer|min:1|max:8',
            ]);
            // dd($validated);


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
            $response = MasterPengajuan::delete($id);
            return redirect()->route('master-pengajuan.index')
                ->with('success', 'Master pengajuan deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('master-pengajuan.index')
                ->with('error', 'Failed to delete master pengajuan: ' . $e->getMessage());
        }
    }
}
