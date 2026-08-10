<x-filament-panels::page>
    @php($summary = $this->summary)
    @php($maxTopDinas = max(array_column($summary['topDinas'], 'count') ?: [1]))
    @php($maxTopLayanan = max(array_column($summary['topLayanan'], 'count') ?: [1]))

    <style>
        .report-wrap {
            display: grid;
            gap: 1.25rem;
        }

        .report-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }

        .report-hero {
            border-radius: 16px;
            padding: 1.25rem;
            color: #ffffff;
            background: linear-gradient(120deg, #0369a1 0%, #1d4ed8 55%, #0f766e 100%);
        }

        .report-hero-title {
            margin: 0;
            font-size: 1.6rem;
            line-height: 1.2;
            font-weight: 800;
        }

        .report-hero-subtitle {
            margin: .4rem 0 0;
            max-width: 740px;
            color: #dbeafe;
            font-size: .95rem;
            line-height: 1.45;
        }

        .report-badge {
            display: inline-block;
            margin-bottom: .6rem;
            padding: .25rem .6rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.15);
            font-size: .7rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .report-hero-grid {
            display: grid;
            gap: 1rem;
        }

        .report-period {
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.08);
            padding: .7rem .85rem;
            align-self: start;
            min-width: 230px;
        }

        .report-period-label {
            margin: 0;
            color: #bfdbfe;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .report-period-value {
            margin: .15rem 0 0;
            font-weight: 700;
            font-size: .95rem;
        }

        .report-controls {
            padding: 1rem;
            display: grid;
            gap: 1rem;
        }

        .report-control-grid {
            display: grid;
            gap: .75rem;
        }

        .report-field {
            display: grid;
            gap: .35rem;
        }

        .report-label {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #475569;
            font-weight: 700;
        }

        .report-input {
            width: 100%;
            height: 40px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 0 .72rem;
            font-size: .9rem;
            background: #ffffff;
            color: #0f172a;
        }

        .report-input:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
        }

        .report-export {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: .85rem;
            background: #f8fafc;
            display: grid;
            gap: .55rem;
        }

        .report-export-title {
            margin: 0;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #475569;
            font-weight: 700;
        }

        .report-export-desc {
            margin: 0;
            color: #64748b;
            font-size: .85rem;
        }

        .report-btn-grid {
            display: grid;
            gap: .5rem;
        }

        .report-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            height: 40px;
            border-radius: 10px;
            color: #ffffff;
            font-size: .86rem;
            font-weight: 700;
        }

        .report-btn-csv { background: #334155; }
        .report-btn-csv:hover { background: #1e293b; }
        .report-btn-xlsx { background: #0369a1; }
        .report-btn-xlsx:hover { background: #075985; }

        .report-kpi-grid {
            display: grid;
            gap: .75rem;
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .report-kpi {
            border-radius: 14px;
            padding: .95rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .report-kpi-label {
            margin: 0;
            font-size: .72rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
        }

        .report-kpi-value {
            margin: .45rem 0 0;
            font-size: 1.8rem;
            line-height: 1;
            font-weight: 800;
            color: #0f172a;
        }

        .report-kpi.success { background: #ecfdf5; border-color: #bbf7d0; }
        .report-kpi.success .report-kpi-label,
        .report-kpi.success .report-kpi-value { color: #166534; }
        .report-kpi.warn { background: #fffbeb; border-color: #fde68a; }
        .report-kpi.warn .report-kpi-label,
        .report-kpi.warn .report-kpi-value { color: #92400e; }
        .report-kpi.danger { background: #fff1f2; border-color: #fecdd3; }
        .report-kpi.danger .report-kpi-label,
        .report-kpi.danger .report-kpi-value { color: #9f1239; }
        .report-kpi.info { background: #eff6ff; border-color: #bfdbfe; }
        .report-kpi.info .report-kpi-label,
        .report-kpi.info .report-kpi-value { color: #1e3a8a; }

        .report-rank-grid {
            display: grid;
            gap: .9rem;
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .report-rank-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: .95rem;
        }

        .report-rank-head {
            margin: 0 0 .75rem;
            padding-bottom: .55rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .report-rank-title {
            margin: 0;
            font-size: 1rem;
            color: #0f172a;
            font-weight: 700;
        }

        .report-rank-sub {
            margin: 0;
            font-size: .75rem;
            color: #64748b;
        }

        .report-rank-list {
            display: grid;
            gap: .55rem;
        }

        .report-rank-item {
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            background: #f8fafc;
            padding: .65rem;
        }

        .report-rank-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem;
        }

        .report-rank-num {
            margin: 0;
            font-size: .72rem;
            color: #94a3b8;
            font-weight: 700;
        }

        .report-rank-name {
            margin: .1rem 0 0;
            color: #334155;
            font-size: .88rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 240px;
        }

        .report-pill {
            border-radius: 999px;
            padding: .2rem .55rem;
            font-size: .72rem;
            font-weight: 700;
            background: #dbeafe;
            color: #1d4ed8;
            flex-shrink: 0;
        }

        .report-pill.alt {
            background: #cffafe;
            color: #0e7490;
        }

        .report-bar {
            margin-top: .5rem;
            height: 6px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .report-bar-fill {
            height: 6px;
            border-radius: 999px;
            background: #0284c7;
            min-width: 8px;
        }

        .report-bar-fill.alt {
            background: #06b6d4;
        }

        .report-empty {
            margin: 0;
            color: #64748b;
            font-size: .88rem;
        }

        @media (min-width: 1024px) {
            .report-hero-grid {
                grid-template-columns: 1fr auto;
                align-items: end;
            }

            .report-controls {
                grid-template-columns: 2fr 1fr;
                align-items: start;
            }

            .report-control-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .report-btn-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .report-kpi-grid {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }

            .report-rank-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>

    <div class="report-wrap">
        <section class="report-hero report-card">
            <div class="report-hero-grid">
                <div>
                    <span class="report-badge">Pusat Laporan</span>
                    <h2 class="report-hero-title">Laporan Pelayanan MPP</h2>
                    <p class="report-hero-subtitle">Pantau performa layanan, lihat ringkasan utama, lalu unduh laporan sesuai periode yang dipilih.</p>
                </div>

                <div class="report-period">
                    <p class="report-period-label">Periode Aktif</p>
                    <p class="report-period-value">{{ $summary['periodLabel'] }}</p>
                </div>
            </div>
        </section>

        <section class="report-card report-controls">
            <div class="report-control-grid">
                <div class="report-field">
                    <label class="report-label">Jenis Laporan</label>
                    <select wire:model.live="reportType" class="report-input">
                        <option value="daily">Harian</option>
                        <option value="monthly">Bulanan</option>
                        <option value="quarterly">Triwulan</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>

                <div class="report-field">
                    <label class="report-label">Tanggal Mulai</label>
                    <input wire:model.live="startDate" type="date" class="report-input" />
                </div>

                <div class="report-field">
                    <label class="report-label">Tanggal Selesai</label>
                    <input wire:model.live="endDate" type="date" @disabled($reportType !== 'custom') class="report-input" />
                </div>
            </div>

            <div class="report-export">
                <p class="report-export-title">Unduh Laporan</p>
                <p class="report-export-desc">File akan mengikuti filter dan periode aktif.</p>
                <div class="report-btn-grid">
                    <a href="{{ $this->csvDownloadUrl }}" class="report-btn report-btn-csv">Export CSV</a>
                    <a href="{{ $this->excelDownloadUrl }}" class="report-btn report-btn-xlsx">Export Excel</a>
                </div>
            </div>
        </section>

        <section class="report-kpi-grid">
            <article class="report-kpi">
                <p class="report-kpi-label">Total Antrian</p>
                <p class="report-kpi-value">{{ number_format($summary['total']) }}</p>
            </article>
            <article class="report-kpi success">
                <p class="report-kpi-label">Selesai</p>
                <p class="report-kpi-value">{{ number_format($summary['completed']) }}</p>
            </article>
            <article class="report-kpi warn">
                <p class="report-kpi-label">Menunggu/Proses</p>
                <p class="report-kpi-value">{{ number_format($summary['pending']) }}</p>
            </article>
            <article class="report-kpi danger">
                <p class="report-kpi-label">Dilewati</p>
                <p class="report-kpi-value">{{ number_format($summary['skipped']) }}</p>
            </article>
            <article class="report-kpi info">
                <p class="report-kpi-label">SKM</p>
                <p class="report-kpi-value">{{ number_format($summary['avgRating'], 2) }}</p>
            </article>
        </section>

        <section class="report-rank-grid">
            <article class="report-rank-card">
                <div class="report-rank-head">
                    <h3 class="report-rank-title">Top Dinas</h3>
                    <p class="report-rank-sub">Top 5</p>
                </div>

                <div class="report-rank-list">
                    @forelse($summary['topDinas'] as $index => $item)
                        @php($percent = $maxTopDinas > 0 ? ($item['count'] / $maxTopDinas) * 100 : 0)
                        <div class="report-rank-item">
                            <div class="report-rank-row">
                                <div>
                                    <p class="report-rank-num">#{{ $index + 1 }}</p>
                                    <p class="report-rank-name">{{ $item['name'] }}</p>
                                </div>
                                <span class="report-pill">{{ number_format($item['count']) }}</span>
                            </div>
                            <div class="report-bar">
                                <div class="report-bar-fill" @style(['width: ' . round($percent, 2) . '%'])></div>
                            </div>
                        </div>
                    @empty
                        <p class="report-empty">Belum ada data pada periode ini.</p>
                    @endforelse
                </div>
            </article>

            <article class="report-rank-card">
                <div class="report-rank-head">
                    <h3 class="report-rank-title">Top Layanan</h3>
                    <p class="report-rank-sub">Top 5</p>
                </div>

                <div class="report-rank-list">
                    @forelse($summary['topLayanan'] as $index => $item)
                        @php($percent = $maxTopLayanan > 0 ? ($item['count'] / $maxTopLayanan) * 100 : 0)
                        <div class="report-rank-item">
                            <div class="report-rank-row">
                                <div>
                                    <p class="report-rank-num">#{{ $index + 1 }}</p>
                                    <p class="report-rank-name">{{ $item['name'] }}</p>
                                </div>
                                <span class="report-pill alt">{{ number_format($item['count']) }}</span>
                            </div>
                            <div class="report-bar">
                                <div class="report-bar-fill alt" @style(['width: ' . round($percent, 2) . '%'])></div>
                            </div>
                        </div>
                    @empty
                        <p class="report-empty">Belum ada data pada periode ini.</p>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
</x-filament-panels::page>
