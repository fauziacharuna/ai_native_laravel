<?php

namespace App\Http\Controllers;

use App\Models\Antrian;

class AntrianTicketController extends Controller
{
    public function show(Antrian $antrian)
    {
        return view('antrian.ticket', [
            'antrian' => $antrian->load(['dinas', 'layanan']),
        ]);
    }
}
