<?php

namespace App\Helpers;

class RoleHelper
{
    public static function toString($masterSurat): string
    {
        // Convert object to array if needed
        if (is_object($masterSurat)) {
            $masterSurat = (array) $masterSurat;
        }

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
