<x-filament-panels::page>
    @php($summary = $this->summary)
    @php($maxTopDinas = max(array_column($summary['topDinas'], 'count') ?: [1]))
    @php($maxTopLayanan = max(array_column($summary['topLayanan'], 'count') ?: [1]))

    <div class="space-y-6">
        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-gradient-to-r from-cyan-700 via-sky-700 to-blue-800 px-6 py-5 text-white">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-cyan-100">Report Center</p>
                        <h2 class="mt-1 text-2xl font-bold">Laporan Pelayanan MPP</h2>
                        <p class="mt-1 text-sm text-cyan-100">Pantau performa layanan dan unduh data berdasarkan periode yang dipilih.</p>
                    </div>
                    <div class="rounded-xl border border-white/25 bg-white/10 px-4 py-2 text-sm">
                        <p class="text-cyan-100">Periode Aktif</p>
                        <p class="font-semibold">{{ $summary['periodLabel'] }}</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <div class="grid gap-4 xl:grid-cols-12">
                    <div class="xl:col-span-9">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="grid gap-3 md:grid-cols-3">
                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-600">Jenis Laporan</label>
                                    <select wire:model.live="reportType" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                        <option value="daily">Harian</option>
                                        <option value="monthly">Bulanan</option>
                                        <option value="quarterly">Triwulan</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-600">Tanggal Mulai</label>
                                    <input wire:model.live="startDate" type="date" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-600">Tanggal Selesai</label>
                                    <input wire:model.live="endDate" type="date" @disabled($reportType !== 'custom') class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 disabled:cursor-not-allowed disabled:bg-slate-100 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="xl:col-span-3">
                        <div class="flex h-full flex-col justify-between rounded-2xl border border-slate-200 bg-white p-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Ekspor</p>
                                <p class="mt-1 text-sm text-slate-600">Unduh data sesuai filter aktif.</p>
                            </div>
                            <div class="mt-4 grid gap-2">
                                <a href="{{ $this->downloadUrl }}" class="inline-flex w-full items-center justify-center rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                                    Download CSV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm ring-1 ring-transparent transition hover:ring-sky-100">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Total Antrian</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($summary['total']) }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm ring-1 ring-transparent transition hover:ring-emerald-100">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">Selesai</p>
                <p class="mt-2 text-2xl font-bold text-emerald-800">{{ number_format($summary['completed']) }}</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm ring-1 ring-transparent transition hover:ring-amber-100">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">Menunggu/Proses</p>
                <p class="mt-2 text-2xl font-bold text-amber-800">{{ number_format($summary['pending']) }}</p>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 shadow-sm ring-1 ring-transparent transition hover:ring-rose-100">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-700">Dilewati</p>
                <p class="mt-2 text-2xl font-bold text-rose-800">{{ number_format($summary['skipped']) }}</p>
            </div>
            <div class="rounded-2xl border border-sky-200 bg-sky-50 p-4 shadow-sm ring-1 ring-transparent transition hover:ring-sky-100">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-sky-700">SKM</p>
                <p class="mt-2 text-2xl font-bold text-sky-800">{{ number_format($summary['avgRating'], 2) }} / 5</p>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-semibold text-slate-900">Top Dinas</h3>
                    <span class="text-xs text-slate-500">Top 5</span>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($summary['topDinas'] as $index => $item)
                        @php($percent = $maxTopDinas > 0 ? ($item['count'] / $maxTopDinas) * 100 : 0)
                        <div class="rounded-xl border border-slate-100 p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-400">#{{ $index + 1 }}</p>
                                    <p class="truncate text-sm font-medium text-slate-700">{{ $item['name'] }}</p>
                                </div>
                                <span class="rounded-full bg-primary-100 px-2.5 py-1 text-xs font-semibold text-primary-700">{{ number_format($item['count']) }}</span>
                            </div>
                            <div class="mt-2 h-1.5 rounded-full bg-slate-100">
                                <div class="h-1.5 rounded-full bg-primary-500" @style(['width: ' . round($percent, 2) . '%'])></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data pada periode ini.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-semibold text-slate-900">Top Layanan</h3>
                    <span class="text-xs text-slate-500">Top 5</span>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($summary['topLayanan'] as $index => $item)
                        @php($percent = $maxTopLayanan > 0 ? ($item['count'] / $maxTopLayanan) * 100 : 0)
                        <div class="rounded-xl border border-slate-100 p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-400">#{{ $index + 1 }}</p>
                                    <p class="truncate text-sm font-medium text-slate-700">{{ $item['name'] }}</p>
                                </div>
                                <span class="rounded-full bg-cyan-100 px-2.5 py-1 text-xs font-semibold text-cyan-700">{{ number_format($item['count']) }}</span>
                            </div>
                            <div class="mt-2 h-1.5 rounded-full bg-slate-100">
                                <div class="h-1.5 rounded-full bg-cyan-500" @style(['width: ' . round($percent, 2) . '%'])></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada data pada periode ini.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-filament-panels::page>
