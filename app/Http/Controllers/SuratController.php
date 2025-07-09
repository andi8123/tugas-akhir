<?php

namespace App\Http\Controllers;

use App\Models\Api\MasterPengajuan;
use App\Models\Api\Pengajuan;
use App\Models\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SuratController extends Controller
{
    public function generatePDF($pengajuanId)
    {
        try {
            $pengajuans = Pengajuan::getAll();
            $pengajuan = collect($pengajuans)->firstWhere('id', $pengajuanId);

            if (!$pengajuan) {
                throw new \Exception('Pengajuan not found');
            }

            $pdf = Pdf::loadView('pdf.surat', compact('pengajuan'));
            return $pdf->download('surat-' . $pengajuan->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load pengajuan: ' . $e->getMessage());
        }
    }
}
