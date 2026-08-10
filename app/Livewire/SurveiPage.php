<?php

namespace App\Livewire;

use App\Models\Dinas;
use App\Models\Layanan;
use App\Models\Survei;
use Livewire\Component;

class SurveiPage extends Component
{
    public ?int $dinas_id = null;
    public ?int $layanan_id = null;
    public int $skor_kepuasan = 5;
    public string $ulasan = '';
    public bool $submitted = false;
    public ?Survei $survei = null;

    public function updatedDinasId(): void
    {
        $this->layanan_id = null;
    }

    public function save(): void
    {
        $this->validate([
            'dinas_id' => 'required|exists:dinas,id',
            'layanan_id' => 'required|exists:layanans,id',
            'skor_kepuasan' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:2000',
        ]);

        $this->survei = Survei::create([
            'dinas_id' => $this->dinas_id,
            'layanan_id' => $this->layanan_id,
            'skor_kepuasan' => $this->skor_kepuasan,
            'ulasan' => $this->ulasan ?: null,
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.survei-page', [
            'dinasList' => Dinas::orderBy('nama')->get(),
            'layananList' => Layanan::when(
                $this->dinas_id,
                fn ($query) => $query->where('dinas_id', $this->dinas_id)
            )->orderBy('nama_layanan')->get(),
        ]);
    }
}
