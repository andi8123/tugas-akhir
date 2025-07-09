<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function getRolesString($masterSurat)
    {
        $roleMap = [
            'is_mhs' => 'Mahasiswa',
            'is_dev' => 'Developer',
            'is_doswal' => 'Dosen Wali',
            'is_prodi' => 'Program Studi',
            'is_admin' => 'Admin',
            'is_dosen' => 'Dosen',
            'is_staff' => 'Staff',
            'is_wk' => 'Wakil Ketua',
            'is_pimpinan' => 'Pimpinan',
            'is_dospem' => 'Dosen Pembimbing',
            'is_marketing' => 'Marketing',
            'is_akademik' => 'Akademik',
            'is_baak' => 'BAAK',
            'is_secretary' => 'Sekretaris',
            'is_bendahara' => 'Bendahara',
            'is_kemahasiswaan' => 'Kemahasiswaan',
        ];

        $activeRoles = [];

        foreach ($masterSurat as $key => $value) {
            if (isset($roleMap[$key]) && $value === true) {
                $activeRoles[] = $roleMap[$key];
            }
        }

        return implode(', ', $activeRoles);
    }
}
