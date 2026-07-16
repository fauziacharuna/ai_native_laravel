<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'nomor_lacak' => 'required|string',
        ]);

        $tracking = Tracking::with(['dinas', 'layanan', 'logs.petugas'])
            ->where('nomor_lacak', trim($request->nomor_lacak))
            ->first();

        return view('tracking.index', [
            'tracking' => $tracking,
            'searched' => true,
            'nomor_lacak' => $request->nomor_lacak,
        ]);
    }
}
