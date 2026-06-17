<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kost;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $kost = Kost::find($request->kost_id);

        if (!$kost) {
            return response()->json([
                'success' => false,
                'message' => 'Kost tidak ditemukan'
            ], 404);
        }

        $totalHarga = $kost->harga * $request->durasi_bulan;

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'kost_id' => $kost->id,
            'tanggal_masuk' => $request->tanggal_masuk,
            'durasi_bulan' => $request->durasi_bulan,
            'total_harga' => $totalHarga,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat',
            'data' => $booking
        ]);
    }

    public function myBookings(Request $request)
    {
        $bookings = Booking::with('kost')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }
    public function ownerBookings(Request $request)
    {
        $ownerId = $request->user()->id;

        $bookings = Booking::with(['user', 'kost'])
            ->whereHas('kost', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }
    public function approve(Request $request, $id)
{
    $booking = Booking::find($id);

    if (!$booking) {
        return response()->json([
            'success' => false,
            'message' => 'Booking tidak ditemukan'
        ], 404);
    }

    $booking->status = 'approved';
    $booking->save();

    return response()->json([
        'success' => true,
        'message' => 'Booking disetujui',
        'data' => $booking
    ]);
}
public function reject(Request $request, $id)
{
    $booking = Booking::find($id);

    if (!$booking) {
        return response()->json([
            'success' => false,
            'message' => 'Booking tidak ditemukan'
        ], 404);
    }

    $booking->status = 'rejected';
    $booking->save();

    return response()->json([
        'success' => true,
        'message' => 'Booking ditolak',
        'data' => $booking
    ]);
}
}