<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelacakan Permohonan - MPP Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
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
                <a href="{{ route('ambil-antrian') }}" class="hover:text-blue-600">Antrian</a>
                <a href="{{ route('tracking.index') }}" class="font-semibold text-blue-700">Tracking</a>
                <a href="{{ route('survey.kepuasan') }}" class="hover:text-blue-600">Survey</a>
            </div>
        </div>
    </nav>

    <section class="bg-gradient-to-r from-blue-700 via-indigo-700 to-sky-700">
        <div class="mx-auto max-w-7xl px-6 py-20 lg:py-24">
            <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                <div>
                    <span class="inline-flex rounded-full bg-white/20 px-4 py-2 text-sm font-medium text-white">
                        Pantau status permohonan Anda
                    </span>
                    <h1 class="mt-6 text-4xl font-bold leading-tight text-white sm:text-5xl">
                        Lacak perkembangan berkas secara real-time
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-relaxed text-blue-100">
                        Masukkan nomor lacak Anda untuk melihat status terkini, riwayat proses, dan estimasi penyelesaian layanan.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('ambil-antrian') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 font-semibold text-blue-700 shadow hover:bg-blue-50">
                            Ambil Antrian
                        </a>
                        <a href="{{ route('survey.kepuasan') }}" class="inline-flex items-center justify-center rounded-xl border border-white/60 px-6 py-3 font-semibold text-white hover:bg-white/10">
                            Isi Survey
                        </a>
                    </div>
                </div>

                <div class="rounded-[28px] border border-white/30 bg-white/95 p-8 shadow-2xl backdrop-blur">
                    <form action="{{ route('tracking.search') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="nomor_lacak" class="mb-2 block text-sm font-semibold text-slate-700">Nomor Lacak</label>
                            <input
                                id="nomor_lacak"
                                type="text"
                                name="nomor_lacak"
                                value="{{ $nomor_lacak ?? '' }}"
                                required
                                autocomplete="off"
                                placeholder="Contoh: MPP-20260716-0001"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                            >
                        </div>
                        <button type="submit" class="w-full rounded-2xl bg-blue-700 px-4 py-3 font-semibold text-white shadow hover:bg-blue-800">
                            Lacak Permohonan
                        </button>
                    </form>

                    <div class="mt-6 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                        <p class="font-semibold text-slate-800">Cara menggunakan</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            <li>Masukkan nomor lacak yang Anda terima saat mendaftar.</li>
                            <li>Periksa perkembangan status layanan secara berkala.</li>
                            <li>Jika ada kendala, gunakan fitur survey untuk memberi masukan.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6 py-16">
        <div class="mx-auto max-w-6xl">
            @if(isset($searched))
                @if($tracking)
                    <div class="overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-xl">
                        <div class="border-b border-slate-200 bg-slate-50 px-8 py-8">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-600">Hasil Penelusuran</p>
                                    <h2 class="mt-2 text-2xl font-bold text-slate-900">Permohonan Anda ditemukan</h2>
                                    <p class="mt-2 text-slate-600">Berikut status dan riwayat perkembangan berkas Anda.</p>
                                </div>
                                <div class="rounded-2xl bg-white px-4 py-3 shadow-sm">
                                    <p class="text-sm text-slate-500">Nomor Lacak</p>
                                    <p class="text-lg font-bold text-blue-700">{{ $tracking->nomor_lacak }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-500">Pemohon</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $tracking->nama_pemohon }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-500">Instansi / Dinas</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $tracking->dinas->nama }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-500">Jenis Layanan</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $tracking->layanan->nama_layanan }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-500">Status Saat Ini</p>
                                    <span class="mt-2 inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
                                        {{ match($tracking->status_sekarang) {
                                            'submitted' => 'Diterima',
                                            'in_process' => 'Diproses',
                                            'hold' => 'Ditangguhkan',
                                            'ready' => 'Siap Diambil',
                                            'completed' => 'Selesai',
                                        } }}
                                    </span>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-500">Estimasi Selesai</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $tracking->tanggal_selesai_estimasi ? $tracking->tanggal_selesai_estimasi->format('d M Y, H:i') : '-' }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-sm text-slate-500">Terakhir Diperbarui</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $tracking->updated_at ? $tracking->updated_at->format('d M Y, H:i') : '-' }}</p>
                                </div>
                            </div>

                            <div class="mt-10">
                                <h3 class="text-xl font-bold text-slate-900">Riwayat Perkembangan Berkas</h3>
                                <div class="mt-6 space-y-5 border-l-2 border-slate-200 pl-6">
                                    @foreach($tracking->logs as $index => $log)
                                        <div class="relative">
                                            <div class="absolute -left-[29px] top-1 h-4 w-4 rounded-full border-4 border-white {{ $index === 0 ? 'bg-blue-600' : 'bg-slate-300' }}"></div>
                                            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                                <div class="flex flex-wrap items-center justify-between gap-2">
                                                    <p class="font-semibold text-slate-900">
                                                        {{ match($log->status) {
                                                            'submitted' => 'Berkas Diterima',
                                                            'in_process' => 'Sedang Diproses',
                                                            'hold' => 'Ditangguhkan (Berkas Kurang)',
                                                            'ready' => 'Siap Diambil',
                                                            'completed' => 'Berkas Diserahkan',
                                                        } }}
                                                    </p>
                                                    <p class="text-sm text-slate-500">{{ $log->created_at->format('d M Y, H:i') }} oleh {{ $log->petugas ? $log->petugas->name : 'Sistem' }}</p>
                                                </div>
                                                @if($log->catatan)
                                                    <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $log->catatan }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-[28px] border border-red-200 bg-red-50 p-8 text-center shadow-sm">
                        <h2 class="text-2xl font-bold text-red-700">Nomor lacak tidak ditemukan</h2>
                        <p class="mt-3 text-slate-600">
                            Maaf, nomor lacak <strong>{{ $nomor_lacak }}</strong> tidak ditemukan dalam sistem. Harap periksa kembali penulisan nomor lacak Anda.
                        </p>
                    </div>
                @endif
            @else
                <div class="rounded-[28px] border border-slate-200 bg-white p-8 text-center shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-900">Cek status permohonan Anda</h2>
                    <p class="mt-3 text-slate-600">Gunakan form di atas untuk melihat perkembangan layanan yang sedang Anda ajukan.</p>
                </div>
            @endif
        </div>
    </section>
</body>
</html>
