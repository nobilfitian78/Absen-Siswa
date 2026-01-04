<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * GET api/absensi
     * optional: ?siswa_id=1
     */
    public function index(Request $request)
    {
        $query = Absensi::with('siswa.kelas');

        if ($request->has('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        return response()->json($query->get());
    }

    /**
     * POST api/absensi
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal'  => 'required|date',
            'status'   => 'required|in:Hadir,Izin,Sakit,Alpha'
        ]);

        $absensi = Absensi::create([
            'siswa_id' => $request->siswa_id,
            'tanggal'  => $request->tanggal,
            'status'   => $request->status
        ]);

        return response()->json([
            'message' => 'Absensi berhasil ditambahkan',
            'data' => $absensi
        ], 201);
    }

    /**
     * GET api/absensi/{id}
     */
    public function show($id)
    {
        $absensi = Absensi::with('siswa.kelas')->findOrFail($id);

        return response()->json($absensi);
    }

    /**
     * PUT api/absensi/{id}
     */
    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'status'  => 'required|in:H,I,S,A'
        ]);

        $absensi->update([
            'tanggal' => $request->tanggal,
            'status'  => $request->status
        ]);

        return response()->json([
            'message' => 'Absensi berhasil diperbarui',
            'data' => $absensi
        ]);
    }

    /**
     * DELETE api/absensi/{id}
     */
    public function destroy($id)
    {
        Absensi::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Absensi berhasil dihapus'
        ]);
    }
}
