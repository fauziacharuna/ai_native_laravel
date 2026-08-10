<div class="min-h-screen bg-slate-50">
    <nav class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6">
            <a href="/" class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 font-bold text-white">
                    MPP
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900">Mal Pelayanan Publik</h1>
                    <p class="text-sm text-slate-500">Portal Pelayanan Publik Terintegrasi</p>
                </div>
            </a>

            <div class="hidden items-center gap-8 text-slate-700 md:flex">
                <a href="/" class="hover:text-blue-600">Beranda</a>
                <a href="{{ route('ambil-antrian') }}" class="font-semibold text-blue-700">Antrian</a>
                <a href="{{ route('tracking.index') }}" class="hover:text-blue-600">Tracking</a>
                <a href="{{ route('survey.kepuasan') }}" class="hover:text-blue-600">Survey</a>
            </div>
        </div>
    </nav>

    <section class="bg-gradient-to-r from-blue-700 via-indigo-700 to-sky-700">
        <div class="mx-auto max-w-7xl px-6 py-20 lg:py-24">
            <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                <div>
                    <span class="inline-flex rounded-full bg-white/20 px-4 py-2 text-sm font-medium text-white">
                        Cepat • Mudah • Transparan
                    </span>
                    <h1 class="mt-6 text-4xl font-bold leading-tight text-white sm:text-5xl">
                        Ambil nomor antrian tanpa antre panjang
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-relaxed text-blue-100">
                        Pilih dinas dan layanan Anda, isi data pemohon, lalu dapatkan nomor antrian digital secara langsung.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('tracking.index') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 font-semibold text-blue-700 shadow hover:bg-blue-50">
                            Cek Tracking
                        </a>
                        <a href="{{ route('survey.kepuasan') }}" class="inline-flex items-center justify-center rounded-xl border border-white/60 px-6 py-3 font-semibold text-white hover:bg-white/10">
                            Isi Survey
                        </a>
                    </div>
                </div>

                <div class="rounded-[28px] border border-white/30 bg-white/95 p-8 shadow-2xl backdrop-blur">
                    <div class="rounded-2xl bg-blue-50 p-4">
                        <p class="text-sm font-semibold text-blue-700">Panduan Singkat</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-slate-600">
                            <li>Pilih dinas dan layanan yang sesuai.</li>
                            <li>Isi nama pemohon dan nomor HP dengan benar.</li>
                            <li>Gunakan nomor antrian untuk mengikuti proses layanan.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6 py-16">
        <div class="mx-auto max-w-5xl">
            <div class="overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-xl">
                <div class="border-b border-slate-200 bg-slate-50 px-8 py-8">
                    <h2 class="text-3xl font-bold text-slate-900">Formulir Ambil Antrian</h2>
                    <p class="mt-2 text-slate-600">Isi data berikut untuk mendapatkan nomor antrian Anda.</p>
                </div>

                <div class="p-8">
                    @if(!$nomor_antrian)
                        <form wire:submit="save" class="space-y-6">
                            <div class="grid gap-6 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block font-semibold text-slate-700">Dinas</label>
                                    <select wire:model.live="dinas_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                        <option value="">Pilih Dinas</option>
                                        @foreach($dinasList as $dinas)
                                            <option value="{{ $dinas->id }}">{{ $dinas->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-2 block font-semibold text-slate-700">Layanan</label>
                                    <select wire:model="layanan_id" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                        <option value="">Pilih Layanan</option>
                                        @foreach($layananList as $layanan)
                                            <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-2 block font-semibold text-slate-700">Nama Pemohon</label>
                                    <input type="text" wire:model="nama_pemohon" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                </div>

                                <div>
                                    <label class="mb-2 block font-semibold text-slate-700">Nomor HP</label>
                                    <input type="text" wire:model="nomor_hp" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                                </div>
                            </div>

                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-700 px-6 py-3 font-semibold text-white shadow hover:bg-blue-800">
                                Ambil Nomor Antrian
                            </button>
                        </form>
                    @else
                        <div class="text-center py-6">
                            <h3 class="text-2xl font-bold text-slate-900">Nomor Antrian Anda</h3>
                            <div class="mt-6 text-8xl font-bold text-blue-700">
                                {{ $nomor_antrian->nomor_antrian }}
                            </div>
                            <div class="mt-4 text-lg text-slate-600">
                                Nomor Urut : {{ $nomor_antrian->nomor_urut }}
                            </div>

                            <div class="mt-8 flex flex-wrap justify-center gap-3">
                                <a href="{{ $this->ticketUrl }}" target="_blank" class="inline-flex items-center justify-center rounded-2xl bg-green-600 px-6 py-3 font-semibold text-white shadow hover:bg-green-700">
                                    Cetak Tiket
                                </a>
                                <button onclick="window.print()" class="inline-flex items-center justify-center rounded-2xl bg-slate-700 px-6 py-3 font-semibold text-white shadow hover:bg-slate-800">
                                    Print Browser
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>