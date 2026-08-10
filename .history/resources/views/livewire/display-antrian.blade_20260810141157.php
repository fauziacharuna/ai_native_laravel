<div class="min-h-screen overflow-hidden bg-slate-50 text-slate-900">
    <div class="flex h-screen flex-col bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.12),_transparent_35%),linear-gradient(180deg,_#f8fafc_0%,_#eef2ff_100%)]">
        <header class="border-b border-white/70 bg-white/95 px-6 py-4 shadow-sm backdrop-blur">
            <div class="mx-auto flex w-full max-w-[1600px] items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-xl font-black text-white shadow-md shadow-blue-200/70">
                        MPP
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold tracking-wide text-slate-900 xl:text-3xl">MAL PELAYANAN PUBLIK</h1>
                        <p class="text-sm text-slate-500">Display antrian hari ini • {{ $today }}</p>
                    </div>
                </div>

                <div class="hidden items-center gap-3 xl:flex">
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-center shadow-sm">
                        <div class="text-2xl font-black text-blue-700">{{ $waitingCount }}</div>
                        <div class="text-xs text-slate-500">Menunggu</div>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-center shadow-sm">
                        <div class="text-2xl font-black text-emerald-700">{{ $callingCount }}</div>
                        <div class="text-xs text-slate-500">Dipanggil</div>
                    </div>
                    <div class="rounded-2xl border border-purple-100 bg-purple-50 px-4 py-3 text-center shadow-sm">
                        <div class="text-2xl font-black text-purple-700">{{ $completedCount }}</div>
                        <div class="text-xs text-slate-500">Selesai</div>
                    </div>
                </div>

                <div class="rounded-2xl border border-blue-100 bg-white px-4 py-2 text-right shadow-sm">
                    <div class="text-3xl font-black leading-none text-blue-700">{{ $timeNow }}</div>
                    <div class="mt-1 text-sm text-slate-500">{{ $today }}</div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-hidden p-4 xl:p-6">
            <div class="mx-auto flex h-full w-full max-w-[1600px] flex-col gap-5">
                @if($currentCalling)
                    <section class="overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-xl shadow-blue-100/60">
                        <div class="grid gap-0 lg:grid-cols-[1.1fr_0.9fr]">
                            <div class="bg-gradient-to-br from-blue-700 to-indigo-800 p-6 text-white">
                                <div class="text-xs font-semibold uppercase tracking-[0.45em] text-blue-100">Antrian Aktif</div>
                                <div class="mt-4 text-7xl font-black leading-none xl:text-[5.5rem]">{{ $currentCalling->nomor_antrian }}</div>
                                <div class="mt-4 text-2xl font-semibold text-blue-50">{{ $currentCalling->nama_pemohon }}</div>
                                <div class="mt-2 text-lg text-blue-100">{{ $currentCalling->dinas->nama ?? '-' }}</div>
                                <div class="mt-1 text-base text-blue-100/90">{{ $currentCalling->layanan->nama_layanan ?? '-' }}</div>
                            </div>

                            <div class="grid gap-4 bg-white p-6">
                                <div class="rounded-[1.5rem] border border-slate-100 bg-slate-50 p-5 shadow-sm">
                                    <div class="text-xs uppercase tracking-[0.35em] text-blue-600">Loket</div>
                                    <div class="mt-2 text-4xl font-black text-slate-900">{{ $currentCalling->dinas->kode ?? 'LOKET' }}</div>
                                    <div class="mt-2 text-sm text-slate-500">Nomor antrian yang sedang ditampilkan</div>
                                </div>

                                <div class="grid grid-cols-3 gap-3">
                                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-3 py-3 text-center shadow-sm">
                                        <div class="text-xl font-black text-blue-700">{{ $waitingCount }}</div>
                                        <div class="text-[11px] text-slate-500">Menunggu</div>
                                    </div>
                                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-3 py-3 text-center shadow-sm">
                                        <div class="text-xl font-black text-emerald-700">{{ $callingCount }}</div>
                                        <div class="text-[11px] text-slate-500">Dipanggil</div>
                                    </div>
                                    <div class="rounded-2xl border border-purple-100 bg-purple-50 px-3 py-3 text-center shadow-sm">
                                        <div class="text-xl font-black text-purple-700">{{ $completedCount }}</div>
                                        <div class="text-[11px] text-slate-500">Selesai</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

                <section class="flex min-h-0 flex-2.5 overflow-hidden rounded-[2rem] border border-blue-100 bg-white shadow-xl shadow-blue-100/60">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="flex flex-col gap-2 xl:flex-row xl:items-center xl:justify-between">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.45em] text-blue-600">Ringkasan Per Dinas</div>
                                <h2 class="mt-1 text-2xl font-bold text-slate-900 xl:text-3xl">Antrian Hari Ini</h2>
                            </div>
                            <div class="text-sm text-slate-500">Pantau nomor aktif tiap dinas secara ringkas</div>
                        </div>
                    </div>

                    <div class="h-[calc(100%-76px)] overflow-y-auto px-4 py-5 pb-10 scroll-smooth">
                        <div class="grid auto-rows-[minmax(320px,auto)] gap-5 md:grid-cols-2 2xl:grid-cols-3">
                            @forelse($dinasSummaries as $summary)
                                <article class="flex min-h-[320px] flex-col overflow-hidden rounded-[1.5rem] border border-slate-100 bg-slate-50 shadow-sm">
                                    <div class="h-2 bg-gradient-to-r from-blue-600 via-indigo-700 to-purple-700"></div>
                                    <div class="border-b border-slate-100 px-4 py-4">
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
                                        <div class="rounded-[1.2rem] border border-blue-100 bg-white p-4 shadow-sm">
                                            <div class="text-[11px] uppercase tracking-[0.35em] text-blue-600">Nomor Aktif</div>
                                            <div class="mt-2 text-4xl font-black leading-none text-slate-900 xl:text-5xl">
                                                {{ $summary->activeQueue?->nomor_antrian ?? '---' }}
                                            </div>
                                            <div class="mt-3 text-sm font-medium text-slate-700">
                                                {{ $summary->activeQueue?->nama_pemohon ?? 'Belum ada antrian aktif' }}
                                            </div>
                                            <div class="mt-1 text-sm text-slate-500">{{ $summary->activeQueue?->layanan?->nama_layanan ?? '-' }}</div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-2 px-4 pb-5 text-center text-sm">
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
                                <div class="rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-slate-500">
                                    Belum ada dinas aktif untuk ditampilkan hari ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <div class="rounded-2xl border border-blue-100 bg-white px-5 py-3 shadow-sm">
                    <div class="overflow-hidden whitespace-nowrap text-base font-medium text-slate-600">
                        <span class="inline-block animate-[marquee_18s_linear_infinite]">
                            Silakan siapkan berkas anda saat nomor antrian dipanggil. Tetap pantau layar informasi antrian untuk update terbaru.
                        </span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
