<?php

namespace App\Livewire;

use App\Models\Antrian;
use App\Models\Dinas;
use Livewire\Component;

class DisplayAntrian extends Component
{
    public function render()
    {
        $today = now()->toDateString();

        $dinasList = Dinas::query()
            ->with(['antrians' => function ($query) use ($today) {
                $query->whereDate('tanggal', '=', $today, 'and')
                    ->with(['layanan', 'dinas'])
                    ->orderBy('status')
                    ->orderBy('nomor_urut');
            }])
            ->orderBy('kode')
            ->get();

        $antrians = $dinasList->flatMap(fn (Dinas $dinas) => $dinas->antrians)->values();

        $waitingCount = $antrians->where('status', 'waiting')->count();
        $callingCount = $antrians->where('status', 'calling')->count();
        $completedCount = $antrians->where('status', 'completed')->count();

        $currentCalling = $antrians->firstWhere('status', 'calling');

        return view('livewire.display-antrian', [
            'antrians' => $antrians,
            'dinasList' => $dinasList,
            'today' => now()->parse($today)->translatedFormat('d F Y'),
            'waitingCount' => $waitingCount,
            'callingCount' => $callingCount,
            'completedCount' => $completedCount,
            'currentCalling' => $currentCalling,
        ]);
    }
}
