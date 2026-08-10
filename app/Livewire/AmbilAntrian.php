<?php

namespace App\Livewire;

use App\Models\Antrian;
use App\Models\Dinas;
use App\Models\Layanan;
use Livewire\Component;

class AmbilAntrian extends Component
{
    public $dinas_id;
    public $layanan_id;
    public $nama_pemohon;
    public $nomor_hp;

    public $nomor_antrian = null;

    public function save()
    {
        $this->validate([
            'dinas_id' => 'required',
            'layanan_id' => 'required',
            'nama_pemohon' => 'required',
            'nomor_hp' => 'required',
        ]);

        $dinas = Dinas::findOrFail($this->dinas_id);

        $today = now()->toDateString();

        $lastUrut = Antrian::where('dinas_id', $this->dinas_id)
            ->whereDate('tanggal', $today)
            ->max('nomor_urut');

        $nomorUrut = ($lastUrut ?? 0) + 1;

        $nomorAntrian = $dinas->kode . '-' .
            str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

        $antrian = Antrian::create([
            'dinas_id' => $this->dinas_id,
            'layanan_id' => $this->layanan_id,
            'nomor_antrian' => $nomorAntrian,
            'nomor_urut' => $nomorUrut,
            'tanggal' => now(),
            'nama_pemohon' => $this->nama_pemohon,
            'nomor_hp' => $this->nomor_hp,
            'tipe_pendaftaran' => 'online',
            'status' => 'waiting',
            'waktu_ambil' => now(),
        ]);

        $this->nomor_antrian = $antrian;
    }

    public function getTicketUrlProperty(): string
    {
        return $this->nomor_antrian
            ? route('antrian.ticket', ['antrian' => $this->nomor_antrian->id])
            : '';
    }

    public function render()
    {
        return view('livewire.ambil-antrian', [
            'dinasList' => Dinas::orderBy('nama')->get(),

            'layananList' => Layanan::query()
                ->when(
                    $this->dinas_id,
                    fn ($q) => $q->where('dinas_id', $this->dinas_id)
                )
                ->orderBy('nama_layanan')
                ->get(),
        ]);
    }
}