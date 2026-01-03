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

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diupdate',
            'data' => new KelasResource($kelas->fresh()->load('siswa'))
        ]);
    }

    public function destroy($id)
    {
        Kelas::destroy($id);
        return response()->json(['success' => true, 'message' => 'Kelas berhasil dihapus']);
    }
}