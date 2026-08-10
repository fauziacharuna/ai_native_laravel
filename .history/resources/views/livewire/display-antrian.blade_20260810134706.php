<div class="min-h-screen overflow-hidden bg-slate-50 text-slate-900">
    <div class="flex h-screen flex-col bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.14),_transparent_38%),linear-gradient(180deg,_#f8fafc_0%,_#eef2ff_100%)]">
        <header class="border-b border-white/70 bg-white/90 px-8 py-4 shadow-sm backdrop-blur">
            <div class="flex items-center justify-between gap-6">
                <div>
                    <div class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.35em] text-blue-700">
                        Display Antrian
                    </div>
                    <h1 class="mt-2 text-3xl font-bold tracking-wide text-slate-900">DISPLAY ANTRIAN</h1>
                    <p class="text-sm text-slate-500">{{ $today }}</p>
                </div>

                <div class="grid grid-cols-3 gap-3 text-sm sm:min-w-[420px]">
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-center shadow-sm">
                        <div class="text-2xl font-bold text-blue-700">{{ $waitingCount }}</div>
                        <div class="text-slate-500">Menunggu</div>
                    </div>
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-center shadow-sm">
                        <div class="text-2xl font-bold text-emerald-700">{{ $callingCount }}</div>
                        <div class="text-slate-500">Dipanggil</div>
                    </div>
                    <div class="rounded-2xl border border-purple-100 bg-purple-50 px-4 py-3 text-center shadow-sm">
                        <div class="text-2xl font-bold text-purple-700">{{ $completedCount }}</div>
                        <div class="text-slate-500">Selesai</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-hidden p-6">
            @if($currentCalling)
                <div class="mb-4 rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-700 to-indigo-800 p-5 text-white shadow-xl shadow-blue-200/60">
                    <div class="text-xs uppercase tracking-[0.35em] text-blue-100">Sedang Dipanggil</div>
                    <div class="mt-2 flex items-end justify-between gap-4">
                        <div>
                            <div class="text-5xl font-black leading-none">{{ $currentCalling->nomor_antrian }}</div>
                            <div class="mt-2 text-xl text-blue-50">{{ $currentCalling->nama_pemohon }}</div>
                        </div>
                        <div class="text-right text-sm text-blue-100">
                            <div>{{ $currentCalling->dinas->nama ?? '-' }}</div>
                            <div class="mt-1">{{ $currentCalling->layanan->nama_layanan ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid h-[calc(100vh-240px)] gap-4 overflow-y-auto pr-1 2xl:grid-cols-2">
                @forelse($dinasList as $dinas)
                    @php
                        $waitingQueues = $dinas->antrians->where('status', 'waiting');
                        $callingQueues = $dinas->antrians->where('status', 'calling');
                        $completedQueues = $dinas->antrians->where('status', 'completed');
                    @endphp

                    <article class="flex min-h-[240px] flex-col overflow-hidden rounded-[1.75rem] border border-blue-100 bg-white shadow-xl shadow-blue-100/60">
                        <div class="h-2 bg-gradient-to-r from-blue-600 via-indigo-700 to-purple-700"></div>
                        <div class="border-b border-slate-100 px-5 py-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.4em] text-blue-500">{{ $dinas->kode }}</div>
                                    <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $dinas->nama }}</h3>
                                </div>
                                <div class="rounded-2xl border border-blue-100 bg-white px-3 py-2 text-right shadow-sm">
                                    <div class="text-xs text-slate-400">Total</div>
                                    <div class="text-2xl font-black text-blue-700">{{ $dinas->antrians->count() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 px-5 py-4 text-center text-sm">
                            <div class="rounded-2xl border border-yellow-100 bg-yellow-50 px-3 py-3">
                                <div class="text-xl font-black text-yellow-700">{{ $waitingQueues->count() }}</div>
                                <div class="mt-1 text-slate-500">Menunggu</div>
                            </div>
                            <div class="rounded-2xl border border-blue-100 bg-blue-50 px-3 py-3">
                                <div class="text-xl font-black text-blue-700">{{ $callingQueues->count() }}</div>
                                <div class="mt-1 text-slate-500">Dipanggil</div>
                            </div>
                            <div class="rounded-2xl border border-purple-100 bg-purple-50 px-3 py-3">
                                <div class="text-xl font-black text-purple-700">{{ $completedQueues->count() }}</div>
                                <div class="mt-1 text-slate-500">Selesai</div>
                            </div>
                        </div>

                        <div class="grid flex-1 gap-4 px-5 pb-5 md:grid-cols-3">
                            <div class="rounded-2xl border border-yellow-100 bg-yellow-50/40 p-3">
                                <div class="text-xs uppercase tracking-[0.3em] text-yellow-500">Menunggu</div>
                                <div class="mt-3 space-y-3">
                                    @forelse($waitingQueues->take(4) as $antrian)
                                        <div class="rounded-xl border border-white bg-white px-3 py-2 shadow-sm">
                                            <div class="text-xl font-black text-slate-900">{{ $antrian->nomor_antrian }}</div>
                                            <div class="text-xs text-slate-500">{{ $antrian->nama_pemohon }}</div>
                                        </div>
                                    @empty
                                        <div class="text-sm text-slate-500">Kosong</div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="rounded-2xl border border-blue-100 bg-blue-50/40 p-3">
                                <div class="text-xs uppercase tracking-[0.3em] text-blue-500">Dipanggil</div>
                                <div class="mt-3 space-y-3">
                                    @forelse($callingQueues->take(4) as $antrian)
                                        <div class="rounded-xl border border-white bg-white px-3 py-2 shadow-sm">
                                            <div class="text-xl font-black text-slate-900">{{ $antrian->nomor_antrian }}</div>
                                            <div class="text-xs text-slate-500">{{ $antrian->nama_pemohon }}</div>
                                        </div>
                                    @empty
                                        <div class="text-sm text-slate-500">Kosong</div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="rounded-2xl border border-purple-100 bg-purple-50/40 p-3">
                                <div class="text-xs uppercase tracking-[0.3em] text-purple-500">Selesai</div>
                                <div class="mt-3 space-y-3">
                                    @forelse($completedQueues->take(4) as $antrian)
                                        <div class="rounded-xl border border-white bg-white px-3 py-2 shadow-sm">
                                            <div class="text-xl font-black text-slate-900">{{ $antrian->nomor_antrian }}</div>
                                            <div class="text-xs text-slate-500">{{ $antrian->nama_pemohon }}</div>
                                        </div>
                                    @empty
                                        <div class="text-sm text-slate-500">Kosong</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[1.75rem] border border-dashed border-slate-200 bg-white p-8 text-center text-slate-500">
                        Belum ada dinas aktif untuk ditampilkan hari ini.
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</div>
