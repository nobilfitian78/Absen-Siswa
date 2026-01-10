<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Resources\KelasResource;

class KelasController extends Controller
{
    
    public function store(Request $request)
    {
        $kelas = Kelas::create([
            'nama_kelas' => $request->nama_kelas
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil ditambahkan',
            'data' => new KelasResource($kelas->fresh())
        ], 201);
    }

    // GET api/kelas
    public function index()
    {
        $list = Kelas::with('siswa')->get();
        return KelasResource::collection($list);
    }

    // GET api/kelas/{id}
    public function show($id)
    {
        $kelas = Kelas::with('siswa')->findOrFail($id);
        return new KelasResource($kelas);
    }

    //PUT api/kelas/{id}
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string'
        ]);

        $kelas = Kelas::findOrFail($id);
        $before = $kelas->nama_kelas;
        
        $kelas->nama_kelas = $validated['nama_kelas'];
        $save_result = $kelas->save();
        
        // Force reload dari database
        $kelas->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diupdate',
            'debug' => [
                'before' => $before,
                'after' => $kelas->nama_kelas,
                'save_result' => $save_result,
                'database_check' => Kelas::find($id)->nama_kelas
            ],
            'data' => new KelasResource($kelas->load('siswa'))
        ]);
    }

    // DELETE api/kelas/{id}
    public function destroy($id)
    {
        Kelas::destroy($id);
        return response()->json(['success' => true, 'message' => 'Kelas berhasil dihapus']);
    }
}