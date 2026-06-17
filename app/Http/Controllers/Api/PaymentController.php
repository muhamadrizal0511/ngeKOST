<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function upload(Request $request)
    {
        $booking = Booking::find($request->booking_id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        $bukti = $request->file('bukti_pembayaran')
            ->store('payments', 'public');

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'jumlah_bayar' => $booking->total_harga,
            'bukti_pembayaran' => $bukti,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload',
            'data' => $payment
        ]);
    }

    public function verify($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment tidak ditemukan'
            ], 404);
        }

        $payment->status = 'verified';
        $payment->save();

        $payment->booking->status = 'paid';
        $payment->booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diverifikasi',
            'data' => $payment
        ]);
    }
}