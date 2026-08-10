<div class="min-h-screen overflow-hidden bg-[#0b4f8a] text-white">
    <div class="flex h-screen flex-col bg-[linear-gradient(180deg,_rgba(11,79,138,0.98)_0%,_rgba(7,55,99,0.98)_100%)]">
        <header class="border-b border-cyan-300/20 bg-[#0c5a9d] px-6 py-4 shadow-2xl shadow-black/20">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-white/80 bg-white/10 text-xl font-black">
                        MPP
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-wide xl:text-3xl">MAL PELAYANAN PUBLIK</h1>
                        <p class="text-sm text-cyan-100/90">Display antrian hari ini • {{ $today }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-cyan-300/30 bg-black/20 px-4 py-2 text-right shadow-lg shadow-black/20">
                    <div class="text-3xl font-black leading-none text-yellow-300">{{ $timeNow }}</div>
                    <div class="text-sm text-cyan-100">{{ $today }}</div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-hidden p-4 xl:p-5">
            <div class="grid h-full gap-4 xl:grid-cols-[1.05fr_1.35fr]">
                <section class="flex min-h-0 flex-col overflow-hidden rounded-[1.8rem] border border-cyan-300/20 bg-[#0e5b99] shadow-2xl shadow-black/25">
                    <div class="border-b border-white/10 px-5 py-4">
                        <div class="text-xs font-semibold uppercase tracking-[0.45em] text-cyan-100">Loket Aktif</div>
                        @if($currentCalling)
                            <div class="mt-2 text-4xl font-black xl:text-5xl">{{ $currentCalling->dinas->kode ?? 'LOKET' }}</div>
                        @else
                            <div class="mt-2 text-4xl font-black xl:text-5xl">LOKET</div>
                        @endif
                    </div>

                    <div class="flex-1 p-5">
                        @if($currentCalling)
                            <div class="grid h-full gap-4 lg:grid-rows-[1fr_auto]">
                                <div class="rounded-[1.5rem] border border-cyan-300/20 bg-[#0a4374] p-5 shadow-inner shadow-black/20">
                                    <div class="text-2xl font-bold uppercase tracking-wide text-white xl:text-3xl">NOMOR ANTRIAN</div>
                                    <div class="mt-4 flex items-end gap-3">
                                        <div class="text-7xl font-black leading-none text-yellow-300 xl:text-[6.5rem]">{{ $currentCalling->nomor_antrian }}</div>
                                    </div>
                                    <div class="mt-4 text-xl font-semibold text-cyan-100 xl:text-2xl">{{ $currentCalling->nama_pemohon }}</div>
                                    <div class="mt-2 text-lg text-cyan-100/90">{{ $currentCalling->dinas->nama ?? '-' }}</div>
                                    <div class="mt-1 text-base text-cyan-100/80">{{ $currentCalling->layanan->nama_layanan ?? '-' }}</div>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div class="rounded-2xl border border-white/10 bg-black/15 px-3 py-3 text-center">
                                        <div class="text-xs uppercase tracking-[0.3em] text-cyan-100">Menunggu</div>
                                        <div class="mt-2 text-2xl font-black text-yellow-300">{{ $waitingCount }}</div>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-black/15 px-3 py-3 text-center">
                                        <div class="text-xs uppercase tracking-[0.3em] text-cyan-100">Dipanggil</div>
                                        <div class="mt-2 text-2xl font-black text-white">{{ $callingCount }}</div>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-black/15 px-3 py-3 text-center">
                                        <div class="text-xs uppercase tracking-[0.3em] text-cyan-100">Selesai</div>
                                        <div class="mt-2 text-2xl font-black text-emerald-300">{{ $completedCount }}</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex h-full min-h-[260px] items-center justify-center rounded-[1.5rem] border border-dashed border-white/20 bg-[#0a4374] p-6 text-center">
                                <div>
                                    <div class="text-4xl font-black text-yellow-300 xl:text-5xl">BELUM ADA</div>
                                    <div class="mt-2 text-lg text-cyan-100">Antrian yang sedang dipanggil</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="flex min-h-0 flex-col overflow-hidden rounded-[1.8rem] border border-cyan-300/20 bg-[#0a4f86] shadow-2xl shadow-black/25">
                    <div class="border-b border-white/10 px-5 py-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.45em] text-cyan-100">Ringkasan Per Dinas</div>
                                <h2 class="mt-2 text-2xl font-bold xl:text-3xl">Antrian Hari Ini</h2>
                            </div>
                            <div class="rounded-2xl border border-yellow-300/30 bg-black/20 px-4 py-2 text-right">
                                <div class="text-xs uppercase tracking-[0.3em] text-cyan-100">Total</div>
                                <div class="text-3xl font-black text-yellow-300">{{ $antrians->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 overflow-hidden p-4">
                        <div class="grid h-full gap-4 md:grid-cols-2 xl:grid-cols-3">
                            @forelse($dinasSummaries as $summary)
                                <article class="flex min-h-0 flex-col overflow-hidden rounded-[1.4rem] border border-white/10 bg-[#0f5f9d] shadow-lg shadow-black/20">
                                    <div class="border-b border-white/10 px-4 py-3">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-[11px] uppercase tracking-[0.35em] text-cyan-100">{{ $summary->dinas->kode }}</div>
                                                <h3 class="mt-1 text-lg font-bold text-white">{{ $summary->dinas->nama }}</h3>
                                            </div>
                                            <div class="rounded-xl border border-white/10 bg-black/15 px-3 py-2 text-right">
                                                <div class="text-[10px] uppercase tracking-[0.3em] text-cyan-100">Total</div>
                                                <div class="text-xl font-black text-yellow-300">{{ $summary->totalCount }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-1 px-4 py-4">
                                        <div class="rounded-[1.2rem] border border-cyan-300/20 bg-[#0a4374] px-4 py-4 shadow-inner shadow-black/20">
                                            <div class="text-[11px] uppercase tracking-[0.35em] text-cyan-100">Nomor Aktif</div>
                                            <div class="mt-2 text-5xl font-black leading-none text-yellow-300">
                                                {{ $summary->activeQueue?->nomor_antrian ?? '---' }}
                                            </div>
                                            <div class="mt-3 text-sm text-cyan-100">{{ $summary->activeQueue?->nama_pemohon ?? 'Belum ada antrian aktif' }}</div>
                                            <div class="mt-1 text-sm text-cyan-100/80">{{ $summary->activeQueue?->layanan?->nama_layanan ?? '-' }}</div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-2 px-4 pb-4 text-center text-sm">
                                        <div class="rounded-xl border border-white/10 bg-black/15 px-2 py-2">
                                            <div class="text-lg font-black text-yellow-300">{{ $summary->waitingCount }}</div>
                                            <div class="text-[11px] text-cyan-100">Menunggu</div>
                                        </div>
                                        <div class="rounded-xl border border-white/10 bg-black/15 px-2 py-2">
                                            <div class="text-lg font-black text-white">{{ $summary->callingCount }}</div>
                                            <div class="text-[11px] text-cyan-100">Dipanggil</div>
                                        </div>
                                        <div class="rounded-xl border border-white/10 bg-black/15 px-2 py-2">
                                            <div class="text-lg font-black text-emerald-300">{{ $summary->completedCount }}</div>
                                            <div class="text-[11px] text-cyan-100">Selesai</div>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-[1.4rem] border border-dashed border-white/20 bg-[#0a4374] p-6 text-center text-cyan-100">
                                    Belum ada dinas aktif untuk ditampilkan hari ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <div class="mt-4 rounded-2xl border border-yellow-300/30 bg-black/20 px-5 py-3 shadow-lg shadow-black/20">
                <div class="overflow-hidden whitespace-nowrap text-lg font-semibold text-yellow-300">
                    <span class="inline-block animate-[marquee_18s_linear_infinite]">
                        Silakan siapkan berkas anda saat nomor antrian dipanggil. Tetap pantau layar informasi antrian untuk update terbaru.
                    </span>
                </div>
            </div>
        </main>
    </div>
</div>
