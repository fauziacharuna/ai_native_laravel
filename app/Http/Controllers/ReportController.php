<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Survei;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;

class ReportController extends Controller
{
    public function download(Request $request)
    {
        [$type, $startDate, $endDate] = $this->resolvePeriod($request);

        $fileName = 'laporan_mpp_' . $type . '_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=' . $fileName,
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($type, $startDate, $endDate): void {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['LAPORAN KINERJA PELAYANAN MPP - ' . strtoupper($type)]);
            fputcsv($file, ['Periode: ' . $startDate->format('Y-m-d') . ' s/d ' . $endDate->format('Y-m-d')]);
            fputcsv($file, ['Tanggal Unduh: ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);
            fputcsv($file, ['METRIK KINERJA UTAMA']);

            $antrianQuery = Antrian::query()
                ->where('tanggal', '>=', $startDate->copy()->startOfDay(), 'and')
                ->where('tanggal', '<=', $endDate->copy()->endOfDay(), 'and');

            $totalAntrian = (clone $antrianQuery)->count();
            $completedAntrian = (clone $antrianQuery)->where('status', 'completed')->count();
            $skippedAntrian = (clone $antrianQuery)->where('status', 'skipped')->count();
            $pendingAntrian = (clone $antrianQuery)->whereIn('status', ['waiting', 'calling', 'serving'])->count();

            $ratingQuery = Survei::query()
                ->where('created_at', '>=', $startDate->copy()->startOfDay(), 'and')
                ->where('created_at', '<=', $endDate->copy()->endOfDay(), 'and');
            $avgRating = $ratingQuery->avg('skor_kepuasan') ?? 0;

            fputcsv($file, ['Total Antrian Terdaftar', $totalAntrian]);
            fputcsv($file, ['Antrian Selesai Terlayani', $completedAntrian]);
            fputcsv($file, ['Antrian Dilewati/Batal', $skippedAntrian]);
            fputcsv($file, ['Antrian Menunggu/Proses', $pendingAntrian]);
            fputcsv($file, ['Indeks Kepuasan Masyarakat (SKM)', number_format($avgRating, 2) . ' / 5.0']);
            fputcsv($file, []);

            fputcsv($file, ['RINCIAN KINERJA PER GERAI DINAS']);
            fputcsv($file, ['Kode Dinas', 'Nama Dinas', 'Total Antrian', 'Selesai', 'Dilewati', 'Menunggu/Proses']);

            $dinasBreakdown = DB::table('dinas')
                ->leftJoin('antrians', function ($join) use ($startDate, $endDate): void {
                    $join->on('dinas.id', '=', 'antrians.dinas_id')
                        ->whereBetween('antrians.tanggal', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);
                })
                ->select(
                    'dinas.kode',
                    'dinas.nama',
                    DB::raw('COUNT(antrians.id) as total'),
                    DB::raw('SUM(CASE WHEN antrians.status = \'completed\' THEN 1 ELSE 0 END) as completed'),
                    DB::raw('SUM(CASE WHEN antrians.status = \'skipped\' THEN 1 ELSE 0 END) as skipped'),
                    DB::raw('SUM(CASE WHEN antrians.status IN (\'waiting\', \'calling\', \'serving\') THEN 1 ELSE 0 END) as pending')
                )
                ->groupBy('dinas.id', 'dinas.kode', 'dinas.nama')
                ->get();

            foreach ($dinasBreakdown as $row) {
                fputcsv($file, [$row->kode, $row->nama, $row->total, $row->completed, $row->skipped, $row->pending]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadExcel(Request $request)
    {
        [$type, $startDate, $endDate] = $this->resolvePeriod($request);

        $fileName = 'laporan_mpp_' . $type . '_' . now()->format('Ymd_His') . '.xlsx';
        $tempPath = storage_path('app/' . $fileName);

        $writer = new XlsxWriter();
        $writer->openToFile($tempPath);

        $writer->addRow(Row::fromValues(['LAPORAN KINERJA PELAYANAN MPP - ' . strtoupper($type)]));
        $writer->addRow(Row::fromValues(['Periode: ' . $startDate->format('Y-m-d') . ' s/d ' . $endDate->format('Y-m-d')]));
        $writer->addRow(Row::fromValues(['Tanggal Unduh: ' . now()->format('Y-m-d H:i:s')]));
        $writer->addRow(Row::fromValues([]));
        $writer->addRow(Row::fromValues(['METRIK KINERJA UTAMA']));

        $antrianQuery = Antrian::query()
            ->where('tanggal', '>=', $startDate->copy()->startOfDay(), 'and')
            ->where('tanggal', '<=', $endDate->copy()->endOfDay(), 'and');

        $totalAntrian = (clone $antrianQuery)->count();
        $completedAntrian = (clone $antrianQuery)->where('status', '=', 'completed', 'and')->count();
        $skippedAntrian = (clone $antrianQuery)->where('status', '=', 'skipped', 'and')->count();
        $pendingAntrian = (clone $antrianQuery)->whereIn('status', ['waiting', 'calling', 'serving'])->count();

        $avgRating = Survei::query()
            ->where('created_at', '>=', $startDate->copy()->startOfDay(), 'and')
            ->where('created_at', '<=', $endDate->copy()->endOfDay(), 'and')
            ->avg('skor_kepuasan') ?? 0;

        $writer->addRow(Row::fromValues(['Total Antrian Terdaftar', (string) $totalAntrian]));
        $writer->addRow(Row::fromValues(['Antrian Selesai Terlayani', (string) $completedAntrian]));
        $writer->addRow(Row::fromValues(['Antrian Dilewati/Batal', (string) $skippedAntrian]));
        $writer->addRow(Row::fromValues(['Antrian Menunggu/Proses', (string) $pendingAntrian]));
        $writer->addRow(Row::fromValues(['Indeks Kepuasan Masyarakat (SKM)', number_format($avgRating, 2) . ' / 5.0']));
        $writer->addRow(Row::fromValues([]));

        $writer->addRow(Row::fromValues(['RINCIAN KINERJA PER GERAI DINAS']));
        $writer->addRow(Row::fromValues(['Kode Dinas', 'Nama Dinas', 'Total Antrian', 'Selesai', 'Dilewati', 'Menunggu/Proses']));

        $dinasBreakdown = DB::table('dinas')
            ->leftJoin('antrians', function ($join) use ($startDate, $endDate): void {
                $join->on('dinas.id', '=', 'antrians.dinas_id')
                    ->where('antrians.tanggal', '>=', $startDate->copy()->startOfDay(), 'and')
                    ->where('antrians.tanggal', '<=', $endDate->copy()->endOfDay(), 'and');
            })
            ->select(
                'dinas.kode',
                'dinas.nama',
                DB::raw('COUNT(antrians.id) as total'),
                DB::raw('SUM(CASE WHEN antrians.status = \'completed\' THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN antrians.status = \'skipped\' THEN 1 ELSE 0 END) as skipped'),
                DB::raw('SUM(CASE WHEN antrians.status IN (\'waiting\', \'calling\', \'serving\') THEN 1 ELSE 0 END) as pending')
            )
            ->groupBy('dinas.id', 'dinas.kode', 'dinas.nama')
            ->get();

        foreach ($dinasBreakdown as $row) {
            $writer->addRow(Row::fromValues([
                (string) ($row->kode ?? '-'),
                (string) $row->nama,
                (string) $row->total,
                (string) $row->completed,
                (string) $row->skipped,
                (string) $row->pending,
            ]));
        }

        $writer->close();

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    private function resolvePeriod(Request $request): array
    {
        $type = $request->get('type', 'monthly');
        $startDate = $request->filled('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $endDate = $request->filled('end_date') ? Carbon::parse($request->input('end_date')) : null;

        if ($type === 'daily') {
            $baseDate = $startDate ?? Carbon::today();

            return [$type, $baseDate->copy()->startOfDay(), $baseDate->copy()->endOfDay()];
        }

        if ($type === 'quarterly') {
            $baseDate = $startDate ?? Carbon::today();
            $quarter = (int) ceil($baseDate->month / 3);
            $quarterStart = $baseDate->copy()->month(($quarter - 1) * 3 + 1)->startOfMonth();

            return [$type, $quarterStart, $quarterStart->copy()->endOfQuarter()];
        }

        if ($type === 'custom') {
            if (! $startDate || ! $endDate) {
                $startDate = Carbon::today()->subMonth();
                $endDate = Carbon::today();
            }

            if ($startDate->gt($endDate)) {
                [$startDate, $endDate] = [$endDate, $startDate];
            }

            return [$type, $startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()];
        }

        $baseDate = $startDate ?? Carbon::today();

        return [$type, $baseDate->copy()->startOfMonth(), $baseDate->copy()->endOfMonth()];
    }
}
