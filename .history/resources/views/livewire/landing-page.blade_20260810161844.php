<div>

    {{-- Navbar --}}
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6">

            <div class="flex items-center justify-between h-20">

                <div class="flex items-center gap-4">

                    <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                        MPP
                    </div>

                    <div>
                        <h1 class="font-bold text-lg">
                            Mal Pelayanan Publik
                        </h1>
                        <p class="text-sm text-gray-500">
                            Portal Pelayanan Publik Terintegrasi
                        </p>
                    </div>

                </div>

                <div class="hidden md:flex items-center gap-8">

                    <a href="#layanan" class="hover:text-blue-600">
                        Layanan
                    </a>

                    <a href="/ambil-antrian" class="hover:text-blue-600">
                        Antrian
                    </a>

                    <a href="/tracking" class="hover:text-blue-600">
                        Tracking
                    </a>

                    <a href="{{ route('display-antrian') }}" class="hover:text-blue-600">
                        Display Antrian
                    </a>

                    <a href="#kontak" class="hover:text-blue-600">
                        Kontak
                    </a>

                    <a href="/admin/login" class="rounded-lg border border-blue-200 px-4 py-2 font-medium text-blue-700 hover:bg-blue-50">
                        Login
                    </a>

                </div>

            </div>

        </div>
    </nav>

    {{-- Hero --}}
    <section class="bg-gradient-to-r from-blue-700 to-indigo-800">

        <div class="max-w-7xl mx-auto px-6 py-24">

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <div>

                    <span class="inline-block bg-white/20 text-white px-4 py-2 rounded-full text-sm">
                        Pelayanan Cepat • Mudah • Transparan
                    </span>

                    <h1 class="text-5xl font-bold text-white mt-6 leading-tight">
                        Mal Pelayanan Publik Digital
                    </h1>

                    <p class="text-blue-100 text-lg mt-6 leading-relaxed">

                        Akses berbagai layanan pemerintah dalam satu portal.
                        Ambil antrian online, cek progres layanan, lihat standar pelayanan,
                        dan nikmati pelayanan publik yang lebih cepat dan transparan.

                    </p>

                    <div class="flex flex-wrap gap-4 mt-10">

                        <a
                            href="{{ route('ambil-antrian') }}"
                            class="bg-white text-blue-700 px-8 py-4 rounded-xl font-semibold hover:bg-blue-50"
                        >
                            🎫 Ambil Antrian
                        </a>

                        <a
                            href="{{ route('tracking.index') }}"
                            class="border border-white text-white px-8 py-4 rounded-xl hover:bg-white hover:text-blue-700"
                        >
                            🔍 Tracking Layanan
                        </a>

                    </div>

                </div>

                <div>

                    <div class="bg-white rounded-3xl p-8 shadow-2xl">

                        <h3 class="font-bold text-xl mb-6">
                            Statistik Hari Ini
                        </h3>

                        <div class="grid grid-cols-2 gap-6">

                            <div class="bg-blue-50 rounded-xl p-5">
                                <p class="text-gray-500">
                                    Pengunjung
                                </p>
                                <h4 class="text-3xl font-bold text-blue-700">
                                    {{ number_format($totalAntrian) }}
                                </h4>
                            </div>

                            <div class="bg-green-50 rounded-xl p-5">
                                <p class="text-gray-500">
                                    Gerai Dinas
                                </p>
                                <h4 class="text-3xl font-bold text-green-700">
                                    {{ $totalDinas }}
                                </h4>
                            </div>

                            <div class="bg-yellow-50 rounded-xl p-5">
                                <p class="text-gray-500">
                                    Layanan
                                </p>
                                <h4 class="text-3xl font-bold text-yellow-700">
                                    {{ $totalLayanan }}
                                </h4>
                            </div>

                            <div class="bg-purple-50 rounded-xl p-5">
                                <p class="text-gray-500">
                                    Online
                                </p>
                                <h4 class="text-3xl font-bold text-purple-700">
                                    24 Jam
                                </h4>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- Statistik --}}
    <section class="py-10 bg-slate-50">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-5">

            <!-- <span class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm font-medium">
                Statistik Pelayanan
            </span> -->

            <h2 class="mt-4 text-4xl font-bold text-slate-900">
                Data Layanan MPP
            </h2>

            <p class="mt-3 text-slate-500">
                Informasi penggunaan layanan publik secara real-time.
            </p>

        </div>

        <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- Gerai Dinas --}}
            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500 to-blue-700 p-6 text-white shadow-lg transition hover:-translate-y-2 hover:shadow-2xl">

                <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-white/10 -mr-10 -mt-10"></div>

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-blue-100 text-sm">
                            Gerai Dinas
                        </p>

                        <h3 class="text-5xl font-bold mt-3">
                            {{ $totalDinas }}
                        </h3>

                        <span class="inline-block mt-4 text-xs bg-white/20 px-3 py-1 rounded-full">
                            Instansi Aktif
                        </span>
                    </div>

                    <div class="text-5xl opacity-80">
                        🏢
                    </div>

                </div>

            </div>

            {{-- Layanan --}}
            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 to-green-700 p-6 text-white shadow-lg transition hover:-translate-y-2 hover:shadow-2xl">

                <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-white/10 -mr-10 -mt-10"></div>

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-green-100 text-sm">
                            Jenis Layanan
                        </p>

                        <h3 class="text-5xl font-bold mt-3">
                            {{ $totalLayanan }}
                        </h3>

                        <span class="inline-block mt-4 text-xs bg-white/20 px-3 py-1 rounded-full">
                            Tersedia
                        </span>
                    </div>

                    <div class="text-5xl opacity-80">
                        📋
                    </div>

                </div>

            </div>

            {{-- Pengguna --}}
            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-violet-500 to-purple-700 p-6 text-white shadow-lg transition hover:-translate-y-2 hover:shadow-2xl">

                <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-white/10 -mr-10 -mt-10"></div>

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-violet-100 text-sm">
                            Total Pengguna
                        </p>

                        <h3 class="text-5xl font-bold mt-3">
                            {{ number_format($totalAntrian) }}
                        </h3>

                        <span class="inline-block mt-4 text-xs bg-white/20 px-3 py-1 rounded-full">
                            Masyarakat Terlayani
                        </span>
                    </div>

                    <div class="text-5xl opacity-80">
                        👥
                    </div>

                </div>

            </div>

            {{-- Kepuasan --}}
            <div class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-orange-500 to-red-600 p-6 text-white shadow-lg transition hover:-translate-y-2 hover:shadow-2xl">

                <div class="absolute right-0 top-0 h-28 w-28 rounded-full bg-white/10 -mr-10 -mt-10"></div>

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-orange-100 text-sm">
                            Kepuasan Masyarakat
                        </p>

                        <h3 class="text-5xl font-bold mt-3">
                            98%
                        </h3>

                        <span class="inline-block mt-4 text-xs bg-white/20 px-3 py-1 rounded-full">
                            Sangat Baik
                        </span>
                    </div>

                    <div class="text-5xl opacity-80">
                        ⭐
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

    {{-- Layanan --}}
    <section id="layanan" class="bg-gray-50 py-24">

        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-16">

                <h2 class="text-4xl font-bold">
                    Layanan Publik Digital
                </h2>

                <p class="text-gray-500 mt-4">
                    Semua layanan tersedia dalam satu portal.
                </p>

            </div>

            <div class="grid md:grid-cols-4 gap-8">

                <a href="{{ route('ambil-antrian') }}"
                    class="bg-white rounded-2xl p-8 shadow hover:shadow-lg">

                    <div class="text-5xl">
                        🎫
                    </div>

                    <h3 class="font-bold text-lg mt-6">
                        Ambil Antrian
                    </h3>

                    <p class="text-gray-500 mt-3">
                        Ambil nomor antrian secara online.
                    </p>

                </a>

                <a href="#tracking"
                    class="bg-white rounded-2xl p-8 shadow hover:shadow-lg">

                    <div class="text-5xl">
                        🔍
                    </div>

                    <h3 class="font-bold text-lg mt-6">
                        Tracking Layanan
                    </h3>

                    <p class="text-gray-500 mt-3">
                        Pantau progres layanan Anda.
                    </p>

                </a>

                <a href="#"
                    class="bg-white rounded-2xl p-8 shadow hover:shadow-lg">

                    <div class="text-5xl">
                        📋
                    </div>

                    <h3 class="font-bold text-lg mt-6">
                        Standar Pelayanan
                    </h3>

                    <p class="text-gray-500 mt-3">
                        Informasi persyaratan dan prosedur.
                    </p>

                </a>

                <a href="{{ route('survey.kepuasan') }}"
                    class="bg-white rounded-2xl p-8 shadow hover:shadow-lg">

                    <div class="text-5xl">
                        ⭐
                    </div>

                    <h3 class="font-bold text-lg mt-6">
                        Survey Kepuasan
                    </h3>

                    <p class="text-gray-500 mt-3">
                        Berikan penilaian pelayanan.
                    </p>

                </a>

            </div>

        </div>

    </section>

    {{-- CTA --}}
    <section id="antrian" class="bg-blue-700 py-24">

        <div class="max-w-4xl mx-auto text-center px-6">

            <h2 class="text-4xl font-bold text-white">
                Ambil Antrian Sebelum Datang
            </h2>

            <p class="text-blue-100 mt-6 text-lg">
                Kurangi waktu tunggu dengan mengambil nomor antrian secara online.
            </p>

            <a
                href="{{ route('ambil-antrian') }}"
                class="inline-block mt-10 bg-white text-blue-700 px-8 py-4 rounded-xl font-semibold"
            >
                Ambil Nomor Antrian
            </a>

        </div>

    </section>

    {{-- Footer --}}
    <footer id="kontak" class="bg-slate-900 text-white">

        <div class="max-w-7xl mx-auto px-6 py-16">

            <div class="grid md:grid-cols-3 gap-12">

                <div>

                    <h3 class="font-bold text-xl">
                        Mal Pelayanan Publik
                    </h3>

                    <p class="text-slate-400 mt-4">
                        Portal pelayanan publik digital yang terintegrasi,
                        cepat, transparan dan mudah diakses masyarakat.
                    </p>

                </div>

                <div>

                    <h3 class="font-bold text-xl">
                        Kontak
                    </h3>

                    <ul class="mt-4 space-y-2 text-slate-400">

                        <li>📞 (0548) 000000</li>
                        <li>✉️ mpp@pemda.go.id</li>
                        <li>🌐 www.pemda.go.id</li>

                    </ul>

                </div>

                <div>

                    <h3 class="font-bold text-xl">
                        Alamat
                    </h3>

                    <p class="text-slate-400 mt-4">
                        Gedung Mal Pelayanan Publik<br>
                        Pemerintah Daerah
                    </p>

                </div>

            </div>

            <div class="border-t border-slate-800 mt-12 pt-8 text-center text-slate-500">

                © {{ date('Y') }} Mal Pelayanan Publik Digital.

            </div>

        </div>

    </footer>

</div>