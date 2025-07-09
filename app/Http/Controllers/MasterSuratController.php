<?php

namespace App\Http\Controllers;

use App\Models\Api\MasterSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MasterSuratController extends Controller
{
    public function index()
    {
        try {
            $masterSurat = MasterSurat::getAll();
            return view('master-surat.index', ['masterSuratList' => $masterSurat]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load master surat: ' . $e->getMessage());
        }
    }

    public function add()
    {
        return view('master-surat.add');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_surat' => 'required|string',
                'isi_surat' => 'required|string',
            ]);

            MasterSurat::create($request->all());
            return redirect()->route('master-surat.index')
                ->with('success', 'Master surat created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create master surat: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        try {
            $allData = MasterSurat::getAll();
            $masterSurat = collect($allData)->firstWhere('id', $id);

            if (!$masterSurat) {
                throw new \Exception('Master surat not found');
            }

            return view('master-surat.edit', ['masterSurat' => $masterSurat]);
        } catch (\Exception $e) {
            return redirect()->route('master-surat.index')
                ->with('error', 'Failed to edit master surat: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_surat' => 'required|string',
                'isi_surat' => 'required|string',
            ]);

            MasterSurat::update($id, $request->all());
            return redirect()->route('master-surat.index')
                ->with('success', 'Master surat updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update master surat: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            MasterSurat::delete($id);
            return redirect()->route('master-surat.index')
                ->with('success', 'Master surat deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('master-surat.index')
                ->with('error', 'Failed to delete master surat: ' . $e->getMessage());
        }
    }
}
