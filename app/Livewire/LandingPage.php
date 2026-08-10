<?php

namespace App\Livewire;

use App\Models\Antrian;
use App\Models\Dinas;
use App\Models\Layanan;
use Livewire\Component;

class LandingPage extends Component
{
    public function render()
    {
        return view('livewire.landing-page', [
            'totalDinas' => Dinas::count(),
            'totalLayanan' => Layanan::count(),
            'totalAntrian' => Antrian::count(),
        ]);
    }
}