<div class="min-h-screen overflow-hidden bg-slate-50 text-slate-900">
    <div class="flex h-screen flex-col bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.12),_transparent_35%),linear-gradient(180deg,_#f8fafc_0%,_#eef2ff_100%)]">
        <header class="border-b border-white/70 bg-white/90 px-6 py-4 shadow-sm backdrop-blur">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-xl font-black text-white shadow-md shadow-blue-200/70">
                        MPP
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-wide text-slate-900 xl:text-3xl">MAL PELAYANAN PUBLIK</h1>
                        <p class="text-sm text-slate-500">Display antrian hari ini • {{ $today }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-blue-100 bg-white px-4 py-2 text-right shadow-sm">
                    <div class="text-3xl font-black leading-none text-blue-700">{{ $timeNow }}</div>
                    <div class="text-sm text-slate-500">{{ $today }}</div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-hidden p-4 xl:p-5">
            <div class="grid h-full gap-4 xl:grid-cols-[1.05fr_1.35fr]">
                <section class="flex min-h-0 flex-col overflow-hidden rounded-[1.8rem] border border-blue-100 bg-white shadow-xl shadow-blue-100/60">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="text-xs font-semibold uppercase tracking-[0.45em] text-blue-600">Loket Aktif</div>
                        @if($currentCalling)
                            <div class="mt-2 text-4xl font-black text-slate-900 xl:text-5xl">{{ $currentCalling->dinas->kode ?? 'LOKET' }}</div>
                        @else
                            <div class="mt-2 text-4xl font-black text-slate-900 xl:text-5xl">LOKET</div>
                        @endif
                    </div>

                    <div class="flex-1 p-5">
                        @if($currentCalling)
                            <div class="grid h-full gap-4 lg:grid-rows-[1fr_auto]">
                                <div class="rounded-[1.5rem] border border-blue-100 bg-gradient-to-br from-blue-700 to-indigo-800 p-5 shadow-lg shadow-blue-200/60">
                                    <div class="text-2xl font-bold uppercase tracking-wide text-white xl:text-3xl">NOMOR ANTRIAN</div>
                                    <div class="mt-4 flex items-end gap-3">
                                        <div class="text-7xl font-black leading-none text-yellow-300 xl:text-[6.5rem]">{{ $currentCalling->nomor_antrian }}</div>
                                    </div>
                                    <div class="mt-4 text-xl font-semibold text-blue-50 xl:text-2xl">{{ $currentCalling->nama_pemohon }}</div>
                                    <div class="mt-2 text-lg text-blue-100">{{ $currentCalling->dinas->nama ?? '-' }}</div>
                                    <div class="mt-1 text-base text-blue-100/80">{{ $currentCalling->layanan->nama_layanan ?? '-' }}</div>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-3 py-3 text-center shadow-sm">
                                        <div class="text-xs uppercase tracking-[0.3em] text-blue-600">Menunggu</div>
                                        <div class="mt-2 text-2xl font-black text-yellow-300">{{ $waitingCount }}</div>
                                    </div>
                                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-3 py-3 text-center shadow-sm">
                                        <div class="text-xs uppercase tracking-[0.3em] text-emerald-600">Dipanggil</div>
                                        <div class="mt-2 text-2xl font-black text-emerald-700">{{ $callingCount }}</div>
                                    </div>
                                    <div class="rounded-2xl border border-purple-100 bg-purple-50 px-3 py-3 text-center shadow-sm">
                                        <div class="text-xs uppercase tracking-[0.3em] text-purple-600">Selesai</div>
                                        <div class="mt-2 text-2xl font-black text-emerald-300">{{ $completedCount }}</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex h-full min-h-[260px] items-center justify-center rounded-[1.5rem] border border-dashed border-blue-100 bg-slate-50 p-6 text-center">
                                <div>
                                    <div class="text-4xl font-black text-slate-900 xl:text-5xl">BELUM ADA</div>
                                    <div class="mt-2 text-lg text-slate-500">Antrian yang sedang dipanggil</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="flex min-h-0 flex-col overflow-hidden rounded-[1.8rem] border border-blue-100 bg-white shadow-xl shadow-blue-100/60">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.45em] text-blue-600">Ringkasan Per Dinas</div>
                                <h2 class="mt-2 text-2xl font-bold text-slate-900 xl:text-3xl">Antrian Hari Ini</h2>
                            </div>
                            <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-2 text-right shadow-sm">
                                <div class="text-xs uppercase tracking-[0.3em] text-blue-600">Total</div>
                                <div class="text-3xl font-black text-blue-700">{{ $antrians->count() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 overflow-hidden p-4">
                        <div class="grid h-full gap-4 md:grid-cols-2 xl:grid-cols-3">
                            @forelse($dinasSummaries as $summary)
                                <article class="flex min-h-0 flex-col overflow-hidden rounded-[1.4rem] border border-slate-100 bg-slate-50 shadow-lg shadow-blue-100/60">
                                    <div class="h-2 bg-gradient-to-r from-blue-600 via-indigo-700 to-purple-700"></div>
                                    <div class="border-b border-slate-100 px-4 py-3">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="text-[11px] uppercase tracking-[0.35em] text-blue-600">{{ $summary->dinas->kode }}</div>
                                                <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $summary->dinas->nama }}</h3>
                                            </div>
                                            <div class="rounded-xl border border-blue-100 bg-white px-3 py-2 text-right shadow-sm">
                                                <div class="text-[10px] uppercase tracking-[0.3em] text-slate-400">Total</div>
                                                <div class="text-xl font-black text-blue-700">{{ $summary->totalCount }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-1 px-4 py-4">
                                        <div class="rounded-[1.2rem] border border-blue-100 bg-white px-4 py-4 shadow-sm">
                                            <div class="text-[11px] uppercase tracking-[0.35em] text-blue-600">Nomor Aktif</div>
                                            <div class="mt-2 text-5xl font-black leading-none text-slate-900">
                                                {{ $summary->activeQueue?->nomor_antrian ?? '---' }}
                                            </div>
                                            <div class="mt-3 text-sm text-slate-500">{{ $summary->activeQueue?->nama_pemohon ?? 'Belum ada antrian aktif' }}</div>
                                            <div class="mt-1 text-sm text-slate-400">{{ $summary->activeQueue?->layanan?->nama_layanan ?? '-' }}</div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-2 px-4 pb-4 text-center text-sm">
                                        <div class="rounded-xl border border-blue-100 bg-blue-50 px-2 py-2 shadow-sm">
                                            <div class="text-lg font-black text-blue-700">{{ $summary->waitingCount }}</div>
                                            <div class="text-[11px] text-slate-500">Menunggu</div>
                                        </div>
                                        <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-2 py-2 shadow-sm">
                                            <div class="text-lg font-black text-emerald-700">{{ $summary->callingCount }}</div>
                                            <div class="text-[11px] text-slate-500">Dipanggil</div>
                                        </div>
                                        <div class="rounded-xl border border-purple-100 bg-purple-50 px-2 py-2 shadow-sm">
                                            <div class="text-lg font-black text-purple-700">{{ $summary->completedCount }}</div>
                                            <div class="text-[11px] text-slate-500">Selesai</div>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-[1.4rem] border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-slate-500">
                                    Belum ada dinas aktif untuk ditampilkan hari ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <div class="mt-4 rounded-2xl border border-blue-100 bg-white px-5 py-3 shadow-sm">
                <div class="overflow-hidden whitespace-nowrap text-lg font-semibold text-blue-700">
                    <span class="inline-block animate-[marquee_18s_linear_infinite]">
                        Silakan siapkan berkas anda saat nomor antrian dipanggil. Tetap pantau layar informasi antrian untuk update terbaru.
                    </span>
                </div>
            </div>
        </main>
    </div>
</div>
