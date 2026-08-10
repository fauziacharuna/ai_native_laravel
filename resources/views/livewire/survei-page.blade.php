<div class="min-h-screen bg-slate-50">
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between h-20">
                <a href="/" class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                        MPP
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-slate-900">Mal Pelayanan Publik</h1>
                        <p class="text-sm text-slate-500">Portal Pelayanan Publik Terintegrasi</p>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-8 text-slate-700">
                    <a href="/" class="hover:text-blue-600">Beranda</a>
                    <a href="{{ route('ambil-antrian') }}" class="hover:text-blue-600">Antrian</a>
                    <a href="{{ route('tracking.index') }}" class="hover:text-blue-600">Tracking</a>
                    <a href="{{ route('survey.kepuasan') }}" class="font-semibold text-blue-700">Survey</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="bg-gradient-to-r from-blue-700 via-indigo-700 to-sky-700">
        <div class="max-w-7xl mx-auto px-6 py-20 lg:py-24">
            <div class="grid lg:grid-cols-[1.2fr_0.8fr] gap-10 items-center">
                <div>
                    <span class="inline-flex items-center rounded-full bg-white/20 px-4 py-2 text-sm font-medium text-white">
                        Pelayanan Cepat • Mudah • Transparan
                    </span>
                    <h1 class="mt-6 text-4xl font-bold leading-tight text-white sm:text-5xl">
                        Bagikan pengalaman Anda lewat survei kepuasan
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-relaxed text-blue-100">
                        Masukan Anda membantu kami memperbaiki kualitas layanan publik agar lebih cepat, ramah, dan terpercaya.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="/" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 font-semibold text-blue-700 shadow hover:bg-blue-50">
                            Kembali ke Beranda
                        </a>
                        <a href="{{ route('ambil-antrian') }}" class="inline-flex items-center justify-center rounded-xl border border-white/60 px-6 py-3 font-semibold text-white hover:bg-white/10">
                            Ambil Antrian
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-8 shadow-2xl">
                    <h2 class="text-xl font-bold text-slate-900">Kenapa survei penting?</h2>
                    <div class="mt-6 space-y-4 text-sm text-slate-600">
                        <div class="rounded-2xl bg-blue-50 p-4">
                            <p class="font-semibold text-blue-700">Feedback langsung dari masyarakat</p>
                            <p class="mt-1">Kami menerima masukan nyata untuk meningkatkan kualitas layanan.</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-50 p-4">
                            <p class="font-semibold text-emerald-700">Layanan jadi lebih responsif</p>
                            <p class="mt-1">Setiap penilaian membantu kami memperbaiki titik yang perlu diperbaiki.</p>
                        </div>
                        <div class="rounded-2xl bg-amber-50 p-4">
                            <p class="font-semibold text-amber-700">Transparansi pelayanan</p>
                            <p class="mt-1">Kepuasan masyarakat menjadi indikator keberhasilan layanan publik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-slate-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-xl">
                <div class="bg-gradient-to-r from-slate-900 to-slate-700 px-8 py-8 text-white">
                    <h2 class="text-3xl font-bold">Survey Kepuasan Masyarakat</h2>
                    <p class="mt-3 max-w-2xl text-slate-200">
                        Berikan penilaian Anda terhadap layanan publik. Masukan Anda akan membantu kami meningkatkan kualitas pelayanan.
                    </p>
                </div>

                <div class="p-8">
                    @if($submitted)
                        <div class="rounded-3xl border border-green-200 bg-green-50 p-8 text-center">
                            <div class="text-3xl font-bold text-green-700">Terima kasih!</div>
                            <p class="mt-4 text-slate-600">
                                Penilaian Anda telah tersimpan.
                            </p>

                            <div class="mt-6 inline-flex items-center gap-3 rounded-3xl bg-white p-6 shadow-sm">
                                <span class="text-slate-500">Skor Anda:</span>
                                <span class="text-4xl font-bold text-blue-700">{{ $survei->skor_kepuasan }}</span>
                            </div>

                            <div class="mt-6">
                                <a href="/" class="inline-flex items-center justify-center rounded-xl bg-blue-700 px-6 py-3 text-white shadow hover:bg-blue-800">
                                    Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    @else
                        <form wire:submit.prevent="save" class="space-y-8">
                            <div class="grid gap-6 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block font-semibold text-slate-700">Dinas</label>
                                    <select
                                        wire:model.live="dinas_id"
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none"
                                    >
                                        <option value="">Pilih Dinas</option>
                                        @foreach($dinasList as $dinas)
                                            <option value="{{ $dinas->id }}">{{ $dinas->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('dinas_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block font-semibold text-slate-700">Layanan</label>
                                    <select
                                        wire:model="layanan_id"
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none"
                                    >
                                        <option value="">Pilih Layanan</option>
                                        @foreach($layananList as $layanan)
                                            <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                                        @endforeach
                                    </select>
                                    @error('layanan_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                                <p class="mb-4 font-semibold text-slate-700">Skor Kepuasan</p>
                                <div class="flex flex-wrap gap-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-3 text-slate-700 transition hover:border-blue-500">
                                            <input type="radio" wire:model="skor_kepuasan" value="{{ $i }}" class="h-4 w-4 text-blue-600" />
                                            <span>{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                @error('skor_kepuasan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block font-semibold text-slate-700">Ulasan (opsional)</label>
                                <textarea
                                    wire:model="ulasan"
                                    rows="4"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none"
                                    placeholder="Berikan masukan, saran, atau pengalaman layanan Anda..."
                                ></textarea>
                                @error('ulasan')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-slate-500">Survei hanya membutuhkan beberapa detik dan membantu kami menjadi lebih baik.</p>
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-700 px-6 py-3 text-white shadow hover:bg-blue-800">
                                    Kirim Penilaian
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
