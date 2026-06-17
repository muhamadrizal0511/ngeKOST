<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class KostController extends Controller
{
    // Semua kost (Guest & User)
    public function index()
    {
        $kosts = Kost::with('owner')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $kosts
        ]);
    }

    // Detail kost
    public function show($id)
    {
        $kost = Kost::with('owner')->find($id);

        if (!$kost) {
            return response()->json([
                'success' => false,
                'message' => 'Kost tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kost
        ]);
    }

    // Tambah kost (Owner)
    public function store(Request $request)
{
    $user = $request->user();

    if ($user->role !== 'owner') {
        return response()->json([
            'success' => false,
            'message' => 'Hanya owner yang dapat menambah kost'
        ], 403);
    }

    $fotoPath = null;

    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')
            ->store('kosts', 'public');
    }

    $kost = Kost::create([
        'owner_id' => $user->id,
        'nama_kost' => $request->nama_kost,
        'alamat' => $request->alamat,
        'harga' => $request->harga,
        'kategori' => $request->kategori,
        'deskripsi' => $request->deskripsi,
        'foto' => $fotoPath,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Kost berhasil ditambahkan',
        'data' => $kost,
    ]);
    }
    public function ownerKost(Request $request)
    {
        $kosts = Kost::where('owner_id', $request->user()->id)
                    ->latest()
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $kosts
        ]);
    }
    public function update(Request $request, $id)
{
    $kost = Kost::find($id);

    if (!$kost) {
        return response()->json([
            'success' => false,
            'message' => 'Kost tidak ditemukan'
        ], 404);
    }

    if ($kost->owner_id != $request->user()->id) {
        return response()->json([
            'success' => false,
            'message' => 'Akses ditolak'
        ], 403);
    }

    if ($request->hasFile('foto')) {

        if ($kost->foto) {
            Storage::disk('public')->delete($kost->foto);
        }

        $kost->foto = $request->file('foto')
            ->store('kosts', 'public');
    }

    $kost->nama_kost = $request->nama_kost ?? $kost->nama_kost;
    $kost->alamat = $request->alamat ?? $kost->alamat;
    $kost->harga = $request->harga ?? $kost->harga;
    $kost->kategori = $request->kategori ?? $kost->kategori;
    $kost->deskripsi = $request->deskripsi ?? $kost->deskripsi;
    $kost->latitude = $request->latitude ?? $kost->latitude;
    $kost->longitude = $request->longitude ?? $kost->longitude;

    $kost->save();

    return response()->json([
        'success' => true,
        'message' => 'Kost berhasil diupdate',
        'data' => $kost
    ]);
    }
    public function destroy(Request $request, $id)
{
    $kost = Kost::find($id);

    if (!$kost) {
        return response()->json([
            'success' => false,
            'message' => 'Kost tidak ditemukan'
        ], 404);
    }

    if ($kost->owner_id != $request->user()->id) {
        return response()->json([
            'success' => false,
            'message' => 'Akses ditolak'
        ], 403);
    }

    if ($kost->foto) {
        Storage::disk('public')->delete($kost->foto);
    }

    $kost->delete();

    return response()->json([
        'success' => true,
        'message' => 'Kost berhasil dihapus'
    ]);
    }
    }