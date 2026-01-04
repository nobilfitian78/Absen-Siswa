<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Resources\SiswaResource;
use Illuminate\Database\QueryException;

class SiswaController extends Controller
{
    public function index()
    {
        $list = Siswa::with('kelas')->get();
        return SiswaResource::collection($list);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|unique:siswa,nis',
            'nama_siswa' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id'
        ]);

        try {
            $siswa = Siswa::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
                'data' => new SiswaResource($siswa->fresh()->load('kelas'))
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan siswa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        return new SiswaResource($siswa);
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Siswa berhasil diperbarui',
            'data' => new SiswaResource($siswa->fresh()->load('kelas'))
        ]);
    }

    public function destroy($id)
    {
        Siswa::destroy($id);
        return response()->json(['success' => true, 'message' => 'Siswa dihapus']);
    }
}
