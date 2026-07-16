<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dinas;
use App\Models\Layanan;
use App\Models\Antrian;
use App\Models\Tracking;
use App\Models\TrackingLog;
use App\Models\Survei;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MppSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@mpp.go.id'],
            [
                'name' => 'Admin MPP',
                'password' => Hash::make('password'),
            ]
        );

        // Create other test users if needed
        $petugas = User::updateOrCreate(
            ['email' => 'petugas@mpp.go.id'],
            [
                'name' => 'Petugas Loket 1',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create Dinas
        $dinasData = [
            [
                'nama' => 'Dinas Kependudukan dan Pencatatan Sipil',
                'kode' => 'DUKCAPIL',
                'deskripsi' => 'Melayani administrasi kependudukan seperti KTP-el, KK, Akta Kelahiran, dan Akta Kematian.',
                'jam_operasional' => '08:00 - 15:00',
                'status' => true,
            ],
            [
                'nama' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
                'kode' => 'DPMPTSP',
                'deskripsi' => 'Melayani perizinan usaha, persetujuan bangunan gedung (PBG), dan investasi daerah.',
                'jam_operasional' => '08:00 - 15:00',
                'status' => true,
            ],
            [
                'nama' => 'Badan Pendapatan Daerah',
                'kode' => 'BAPENDA',
                'deskripsi' => 'Melayani pembayaran Pajak Bumi dan Bangunan (PBB) serta pajak daerah lainnya.',
                'jam_operasional' => '08:00 - 14:30',
                'status' => true,
            ],
            [
                'nama' => 'Kantor Imigrasi MPP',
                'kode' => 'IMIGRASI',
                'deskripsi' => 'Melayani pembuatan dan perpanjangan Paspor RI.',
                'jam_operasional' => '08:30 - 15:00',
                'status' => true,
            ],
        ];

        $createdDinas = [];
        foreach ($dinasData as $d) {
            $createdDinas[$d['kode']] = Dinas::updateOrCreate(['kode' => $d['kode']], $d);
        }

        // 3. Create Layanan
        $layananData = [
            'DUKCAPIL' => [
                [
                    'nama_layanan' => 'Pembuatan KTP-el Baru',
                    'persyaratan' => "- Berusia minimal 17 tahun\n- Fotokopi Kartu Keluarga (KK)\n- Surat Pengantar dari RT/RW",
                    'prosedur' => "1. Pemohon datang membawa berkas persyaratan.\n2. Mengambil nomor antrian.\n3. Melakukan perekaman biometrik (foto, retina, sidik jari) di loket.\n4. KTP dicetak dan diserahkan.",
                    'estimasi_waktu' => 1440, // 24 jam / 1 hari
                    'tarif' => 0,
                    'produk' => 'Kartu KTP-el Fisik',
                    'pengaduan' => 'Hubungi WhatsApp Pengaduan DUKCAPIL: 0812-3456-7890',
                    'status' => true,
                ],
                [
                    'nama_layanan' => 'Penerbitan Kartu Keluarga (KK)',
                    'persyaratan' => "- Surat Pengantar RT/RW\n- KK Lama (asli)\n- Fotokopi Surat Nikah/Akta Cerai",
                    'prosedur' => "1. Menyerahkan berkas persyaratan di loket.\n2. Verifikasi berkas oleh petugas.\n3. Entry data ke SIAK.\n4. Tanda tangan elektronik Kepala Dinas.\n5. Cetak KK baru.",
                    'estimasi_waktu' => 2880, // 2 hari
                    'tarif' => 0,
                    'produk' => 'Lembar Kartu Keluarga Asli (TTE)',
                    'pengaduan' => 'Hubungi Call Center DUKCAPIL: 1500-537',
                    'status' => true,
                ],
                [
                    'nama_layanan' => 'Penerbitan Akta Kelahiran',
                    'persyaratan' => "- Surat Keterangan Lahir dari bidan/RS\n- Fotokopi Surat Nikah orang tua\n- Fotokopi KTP orang tua & saksi\n- Fotokopi KK",
                    'prosedur' => "1. Menyerahkan berkas di loket.\n2. Validasi berkas.\n3. Pencatatan di database kependudukan.\n4. Penerbitan kutipan akta kelahiran.",
                    'estimasi_waktu' => 4320, // 3 hari
                    'tarif' => 0,
                    'produk' => 'Kutipan Akta Kelahiran Fisik',
                    'pengaduan' => 'WhatsApp: 0812-3456-7890',
                    'status' => true,
                ],
            ],
            'DPMPTSP' => [
                [
                    'nama_layanan' => 'Persetujuan Bangunan Gedung (PBG) / IMB',
                    'persyaratan' => "- KTP Pemohon\n- Sertifikat Hak Milik (SHM) Tanah\n- Gambar Rencana Teknis Bangunan\n- Surat Pernyataan Tanggung Jawab Teknis",
                    'prosedur' => "1. Pemohon mengajukan berkas secara online melalui SIMBG.\n2. Verifikasi berkas administrasi dan teknis.\n3. Sidang tim profesi ahli (TPA).\n4. Pembayaran retribusi daerah.\n5. PBG diterbitkan.",
                    'estimasi_waktu' => 20160, // 14 hari
                    'tarif' => 500000,
                    'produk' => 'Sertifikat PBG',
                    'pengaduan' => 'Email: dpmptsp@kabupaten.go.id',
                    'status' => true,
                ],
                [
                    'nama_layanan' => 'Persetujuan Kesesuaian Kegiatan Pemanfaatan Ruang (PKKPR)',
                    'persyaratan' => "- Koordinat lokasi\n- Rencana tata letak bangunan\n- Fotokopi KTP & Sertifikat Tanah",
                    'prosedur' => "1. Mengajukan permohonan.\n2. Survei lapangan oleh tim teknis.\n3. Analisis tata ruang.\n4. Penerbitan rekomendasi PKKPR.",
                    'estimasi_waktu' => 10080, // 7 hari
                    'tarif' => 0,
                    'produk' => 'Dokumen PKKPR',
                    'pengaduan' => 'Email: dpmptsp@kabupaten.go.id',
                    'status' => true,
                ],
            ],
            'BAPENDA' => [
                [
                    'nama_layanan' => 'Pembayaran PBB-P2',
                    'persyaratan' => "- SPPT PBB tahun berjalan\n- Nomor Objek Pajak (NOP)",
                    'prosedur' => "1. Menyerahkan NOP ke loket.\n2. Petugas menyebutkan nominal.\n3. Pemohon membayar tunai/non-tunai.\n4. Penyerahan Struk Bukti Pelunasan Pajak (STTS).",
                    'estimasi_waktu' => 15, // 15 menit
                    'tarif' => 0, // Tarif dinamis berdasarkan objek pajak
                    'produk' => 'STTS (Surat Tanda Terima Setoran)',
                    'pengaduan' => 'Hotline BAPENDA: (021) 876-5432',
                    'status' => true,
                ],
            ],
            'IMIGRASI' => [
                [
                    'nama_layanan' => 'Pembuatan Paspor Baru / Perpanjangan',
                    'persyaratan' => "- E-KTP\n- Kartu Keluarga (KK)\n- Akta Lahir / Buku Nikah / Ijazah\n- Paspor lama (bagi yang perpanjangan)",
                    'prosedur' => "1. Booking antrian online melalui aplikasi M-Paspor.\n2. Verifikasi berkas asli di gerai MPP.\n3. Wawancara dan pengambilan foto & sidik jari.\n4. Melakukan pembayaran via bank/pos.\n5. Paspor dapat diambil 3-4 hari kerja kemudian.",
                    'estimasi_waktu' => 5760, // 4 hari
                    'tarif' => 350000,
                    'produk' => 'Buku Paspor RI',
                    'pengaduan' => 'Instagram: @imigrasi_mpp',
                    'status' => true,
                ],
            ],
        ];

        $createdLayanans = [];
        foreach ($layananData as $kode => $layananList) {
            $dinasId = $createdDinas[$kode]->id;
            foreach ($layananList as $l) {
                $l['dinas_id'] = $dinasId;
                $createdLayanans[$kode][] = Layanan::updateOrCreate(
                    [
                        'dinas_id' => $dinasId,
                        'nama_layanan' => $l['nama_layanan']
                    ],
                    $l
                );
            }
        }

        // 4. Create Historical Queues (Antrian) & Trackings & Survei (Past 10 Days)
        $statuses = ['waiting', 'calling', 'serving', 'skipped', 'completed'];
        $registrationTypes = ['online', 'offline'];
        $fakerNames = [
            'Ahmad Fauzi', 'Siti Aminah', 'Budi Santoso', 'Dewi Lestari', 'Joko Widodo',
            'Rini Amalia', 'Hendra Wijaya', 'Sri Wahyuni', 'Andi Pratama', 'Megawati',
            'Faisal Rahman', 'Indah Permata', 'Aditya Nugraha', 'Putri Handayani', 'Yusuf Habibi'
        ];

        // Seed data for the past 10 days
        for ($i = 10; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            foreach ($createdDinas as $kode => $dinas) {
                // Generate 3 - 8 queues per day for each dinas
                $numQueues = rand(3, 8);
                
                for ($q = 1; $q <= $numQueues; $q++) {
                    $layanan = $createdLayanans[$kode][array_rand($createdLayanans[$kode])];
                    $name = $fakerNames[array_rand($fakerNames)];
                    $queueNo = $kode[0] . '-' . str_pad($q, 3, '0', STR_PAD_LEFT); // E.g. D-001, D-002...

                    // If it is today, let status be mixed. If past days, it should be completed or skipped.
                    if ($i === 0) {
                        $status = $statuses[array_rand(['waiting', 'calling', 'serving', 'completed'])];
                    } else {
                        $status = array_rand([0 => 'skipped', 1 => 'completed', 2 => 'completed', 3 => 'completed']) === 0 ? 'skipped' : 'completed';
                    }

                    $waktuAmbil = Carbon::parse($date->format('Y-m-d') . ' ' . rand(8, 14) . ':' . rand(0, 59) . ':' . rand(0, 59));
                    $waktuPanggil = null;
                    $waktuMulai = null;
                    $waktuSelesai = null;

                    if ($status !== 'waiting') {
                        $waktuPanggil = (clone $waktuAmbil)->addMinutes(rand(5, 45));
                        if ($status !== 'skipped') {
                            $waktuMulai = (clone $waktuPanggil)->addMinutes(rand(1, 3));
                            $waktuSelesai = (clone $waktuMulai)->addMinutes(rand(10, 40));
                        }
                    }

                    $antrian = Antrian::create([
                        'dinas_id' => $dinas->id,
                        'layanan_id' => $layanan->id,
                        'nomor_antrian' => $queueNo,
                        'nomor_urut' => $q,
                        'tanggal' => $date->format('Y-m-d'),
                        'nama_pemohon' => $name,
                        'nomor_hp' => '0812' . rand(10000000, 99999999),
                        'tipe_pendaftaran' => $registrationTypes[array_rand($registrationTypes)],
                        'status' => $status,
                        'loket_nomor' => 'Loket ' . rand(1, 3),
                        'waktu_ambil' => $waktuAmbil,
                        'waktu_panggil' => $waktuPanggil,
                        'waktu_mulai_layanan' => $waktuMulai,
                        'waktu_selesai' => $waktuSelesai,
                        'created_at' => $waktuAmbil,
                        'updated_at' => $waktuSelesai ?? $waktuAmbil,
                    ]);

                    // For completed/served queues, let's create a Tracking progress (Service Tracking)
                    if ($status === 'completed' || $status === 'serving') {
                        $trackingStatus = 'completed';
                        if ($status === 'serving') {
                            $trackingStatus = array_rand(['submitted' => 1, 'in_process' => 2, 'hold' => 3]) ?: 'in_process';
                        }
                        
                        $nomorLacak = 'MPP-' . $date->format('Ymd') . '-' . str_pad($antrian->id, 4, '0', STR_PAD_LEFT);
                        
                        $tracking = Tracking::create([
                            'antrian_id' => $antrian->id,
                            'dinas_id' => $dinas->id,
                            'layanan_id' => $layanan->id,
                            'nomor_lacak' => $nomorLacak,
                            'nama_pemohon' => $name,
                            'nomor_hp' => $antrian->nomor_hp,
                            'status_sekarang' => $trackingStatus,
                            'catatan_terakhir' => $trackingStatus === 'hold' ? 'Kekurangan berkas pasfoto 3x4 latar belakang merah 2 lembar.' : 'Layanan diproses.',
                            'tanggal_selesai_estimasi' => (clone $waktuAmbil)->addMinutes($layanan->estimasi_waktu),
                            'created_at' => $waktuAmbil,
                            'updated_at' => $waktuSelesai ?? $waktuAmbil,
                        ]);

                        // Add tracking logs
                        TrackingLog::create([
                            'tracking_id' => $tracking->id,
                            'status' => 'submitted',
                            'petugas_id' => $petugas->id,
                            'catatan' => 'Berkas pendaftaran diterima di loket.',
                            'created_at' => $waktuAmbil,
                        ]);

                        if ($trackingStatus !== 'submitted') {
                            $waktuProses = (clone $waktuAmbil)->addMinutes(5);
                            TrackingLog::create([
                                'tracking_id' => $tracking->id,
                                'status' => 'in_process',
                                'petugas_id' => $petugas->id,
                                'catatan' => 'Berkas diverifikasi dan diproses oleh tim teknis.',
                                'created_at' => $waktuProses,
                            ]);
                        }

                        if ($trackingStatus === 'hold') {
                            $waktuHold = (clone $waktuAmbil)->addMinutes(10);
                            TrackingLog::create([
                                'tracking_id' => $tracking->id,
                                'status' => 'hold',
                                'petugas_id' => $petugas->id,
                                'catatan' => 'Berkas ditangguhkan karena kekurangan pasfoto 3x4 latar belakang merah 2 lembar.',
                                'created_at' => $waktuHold,
                            ]);
                        }

                        if ($trackingStatus === 'completed') {
                            $waktuReady = (clone $waktuAmbil)->addMinutes(25);
                            TrackingLog::create([
                                'tracking_id' => $tracking->id,
                                'status' => 'ready',
                                'petugas_id' => $petugas->id,
                                'catatan' => 'Dokumen / Produk layanan selesai dicetak dan siap diambil.',
                                'created_at' => $waktuReady,
                            ]);

                            $waktuSelesaiLacak = (clone $waktuAmbil)->addMinutes(30);
                            TrackingLog::create([
                                'tracking_id' => $tracking->id,
                                'status' => 'completed',
                                'petugas_id' => $petugas->id,
                                'catatan' => 'Dokumen telah diserahkan secara fisik ke pemohon.',
                                'created_at' => $waktuSelesaiLacak,
                            ]);

                            // Create Survei Kepuasan (Satisfaction Survey)
                            Survei::create([
                                'tracking_id' => $tracking->id,
                                'antrian_id' => $antrian->id,
                                'dinas_id' => $dinas->id,
                                'layanan_id' => $layanan->id,
                                'skor_kepuasan' => rand(4, 5), // mostly positive reviews
                                'ulasan' => array_rand([
                                    'Pelayanan sangat cepat dan petugas ramah.',
                                    'Sangat puas dengan kemudahan layanannya.',
                                    'Mantap, tidak perlu antri lama.',
                                    'Proses transparan dan loketnya nyaman.',
                                    'Pelayanannya baik sekali.',
                                    ''
                                ]),
                                'created_at' => $waktuSelesaiLacak,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
