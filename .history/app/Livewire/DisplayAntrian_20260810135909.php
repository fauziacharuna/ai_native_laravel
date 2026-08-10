<?php

namespace App\Livewire;

use App\Models\Antrian;
use App\Models\Dinas;
use Carbon\Carbon;
use Livewire\Component;

class DisplayAntrian extends Component
{
    public function render()
    {
        $now = Carbon::now('Asia/Makassar');
        $today = $now->toDateString();

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

        $dinasSummaries = $dinasList->map(function (Dinas $dinas) {
            $queues = $dinas->antrians;
            $activeQueue = $queues->firstWhere('status', 'calling')
                ?? $queues->firstWhere('status', 'serving')
                ?? $queues->firstWhere('status', 'waiting')
                ?? $queues->first();

            return (object) [
                'dinas' => $dinas,
                'activeQueue' => $activeQueue,
                'waitingCount' => $queues->where('status', 'waiting')->count(),
                'callingCount' => $queues->where('status', 'calling')->count(),
                'completedCount' => $queues->where('status', 'completed')->count(),
                'totalCount' => $queues->count(),
            ];
        });

        $waitingCount = $antrians->where('status', 'waiting')->count();
        $callingCount = $antrians->where('status', 'calling')->count();
        $completedCount = $antrians->where('status', 'completed')->count();

        $currentCalling = $antrians->firstWhere('status', 'calling');

        return view('livewire.display-antrian', [
            'antrians' => $antrians,
            'dinasList' => $dinasList,
            'dinasSummaries' => $dinasSummaries,
            'today' => $now->locale('id')->translatedFormat('l, d F Y'),
            'timeNow' => $now->format('H:i'),
            'waitingCount' => $waitingCount,
            'callingCount' => $callingCount,
            'completedCount' => $completedCount,
            'currentCalling' => $currentCalling,
        ]);
    }
}
