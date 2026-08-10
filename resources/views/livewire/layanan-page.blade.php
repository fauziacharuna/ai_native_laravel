<div class="bg-slate-50 min-h-screen">

    {{-- HERO --}}
    <section class="bg-gradient-to-r from-blue-700 via-blue-800 to-indigo-900">

        <div class="max-w-7xl mx-auto px-6 py-20">

            <div class="max-w-3xl">

                

                <h1 class="mt-6 text-5xl font-bold text-white">

                    Katalog Layanan Publik

                </h1>

                <p class="mt-5 text-lg text-blue-100">

                    Temukan informasi layanan,
                    persyaratan,
                    prosedur,
                    estimasi waktu,
                    tarif,
                    dan produk layanan dari seluruh gerai dinas.

                </p>

            </div>

        </div>

    </section>

    {{-- FILTER --}}
    <section class="-mt-10">

        <div class="max-w-7xl mx-auto px-6">

            <div class="bg-white rounded-3xl shadow-xl p-6">

                <div class="grid lg:grid-cols-3 gap-4">

                    <div>

                        <input
                            wire:model.live.debounce.300ms="search"
                            type="text"
                            placeholder="Cari layanan..."
                            class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500">

                    </div>

                    <div>

                        <select
                            wire:model.live="dinas"
                            class="w-full rounded-xl border-gray-200">

                            <option value="">
                                Semua Dinas
                            </option>

                            @foreach($dinasList as $dinas)

                                <option value="{{ $dinas->id }}">
                                    {{ $dinas->nama }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <button
                            wire:click="$refresh"
                            class="w-full rounded-xl bg-blue-600 text-white py-3 font-medium">

                            Refresh Data

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- LIST LAYANAN --}}
    <section class="py-16">

        <div class="max-w-7xl mx-auto px-6">

            <div class="flex justify-between items-center mb-8">

                <div>

                    <h2 class="text-3xl font-bold text-slate-800">

                        Daftar Layanan

                    </h2>

                    <p class="text-slate-500 mt-2">

                        {{ $layananList->count() }}
                        layanan ditemukan

                    </p>

                </div>

            </div>

            <div class="grid lg:grid-cols-3 gap-8">

                @forelse($layananList as $layanan)

                    <div
                        class="group bg-white rounded-3xl overflow-hidden shadow hover:shadow-2xl transition duration-300">

                        {{-- HEADER --}}
                        <div
                            class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white">

                            <div class="flex justify-between items-start">

                                <div>

                                    <div
                                        class="text-xs uppercase tracking-widest text-blue-100">

                                        {{ $layanan->dinas->nama }}

                                    </div>

                                    <h3 class="mt-2 text-xl font-bold">

                                        {{ $layanan->nama_layanan }}

                                    </h3>

                                </div>

                                <div
                                    class="bg-white/20 px-3 py-1 rounded-full text-xs">

                                    Aktif

                                </div>

                            </div>

                        </div>

                        {{-- BODY --}}
                        <div class="p-6">

                            <div class="space-y-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="h-10 w-10 rounded-xl bg-blue-100 flex items-center justify-center">

                                        ⏱️

                                    </div>

                                    <div>

                                        <div
                                            class="text-xs text-slate-500">

                                            Estimasi

                                        </div>

                                        <div
                                            class="font-semibold text-slate-700">

                                            {{ $layanan->estimasi_waktu }}
                                            Menit

                                        </div>

                                    </div>

                                </div>

                                <div class="flex items-center gap-3">

                                    <div
                                        class="h-10 w-10 rounded-xl bg-green-100 flex items-center justify-center">

                                        💰

                                    </div>

                                    <div>

                                        <div
                                            class="text-xs text-slate-500">

                                            Tarif

                                        </div>

                                        <div
                                            class="font-semibold text-slate-700">

                                            Rp
                                            {{ number_format($layanan->tarif) }}

                                        </div>

                                    </div>

                                </div>

                                <div class="flex items-center gap-3">

                                    <div
                                        class="h-10 w-10 rounded-xl bg-purple-100 flex items-center justify-center">

                                        📦

                                    </div>

                                    <div>

                                        <div
                                            class="text-xs text-slate-500">

                                            Produk

                                        </div>

                                        <div
                                            class="font-semibold text-slate-700">

                                            {{ $layanan->produk }}

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="mt-6 border-t pt-6">

                                <div
                                    class="line-clamp-3 text-sm text-slate-500">

                                    {{ $layanan->persyaratan }}

                                </div>

                            </div>

                            <div class="mt-6 flex gap-3">

                                <button
                                    class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-medium hover:bg-blue-700">

                                    Detail Layanan

                                </button>

                                <a
                                    href="{{ route('ambil-antrian') }}"
                                    class="px-4 py-3 rounded-xl border border-blue-600 text-blue-600 hover:bg-blue-50">

                                    🎫

                                </a>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="col-span-3">

                        <div
                            class="bg-white rounded-3xl p-16 text-center shadow">

                            <div class="text-6xl">

                                📋

                            </div>

                            <h3
                                class="text-2xl font-bold mt-4 text-slate-700">

                                Data Tidak Ditemukan

                            </h3>

                            <p class="text-slate-500 mt-2">

                                Tidak ada layanan yang sesuai dengan pencarian.

                            </p>

                        </div>

                    </div>

                @endforelse

            </div>

        </div>

    </section>

</div>