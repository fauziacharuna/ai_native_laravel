<?php

namespace App\Livewire;

use App\Models\Layanan;
use Livewire\Component;
use App\Models\Dinas;

class LayananPage extends Component
{
    public $selectedDinas = null;

    public $selectedLayanan = null;

    public $showDetail = false;
    public string $search = '';
public string $dinas = '';

    public function pilihDinas($id)
    {
        $this->selectedDinas = $id;
    }

    public function detailLayanan($id)
    {
        $this->selectedLayanan =
            Layanan::with('dinas')->findOrFail($id);

        $this->showDetail = true;
    }

    public function render()
    {
        // return view('livewire.layanan-page', [
        //     'dinasList' => Dinas::withCount('layanans')->get(),

        //     'layananList' => Layanan::when(
        //         $this->selectedDinas,
        //         fn($q) => $q->where('dinas_id', $this->selectedDinas)
        //     )->get()
        // ]);
        $layananList = Layanan::query()
        ->with('dinas')

        ->when($this->search, function ($query) {
            $query->where('nama_layanan', 'like', '%' . $this->search . '%');
        })

        ->when($this->dinas, function ($query) {
            $query->where('dinas_id', $this->dinas);
        })

        ->get();

    return view('livewire.layanan-page', [
        'dinasList' => Dinas::all(),
        'layananList' => $layananList,
    ]);
    }
}