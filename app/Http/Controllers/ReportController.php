<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Survei;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function download(Request $request)
    {
        $type = $request->get('type', 'monthly'); // daily, monthly, semester
        $date = Carbon::today();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=laporan_mpp_{$type}_" . now()->format('Ymd') . ".csv",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($type, $date) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write Title
            fputcsv($file, ["LAPORAN KINERJA PELAYANAN MPP - " . strtoupper($type)]);
            fputcsv($file, ["Tanggal Unduh: " . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            // Summary Metrics
            fputcsv($file, ["METRIK KINERJA UTAMA"]);
            
            // 1. Total Antrian
            $query = Antrian::query();
            if ($type === 'daily') {
                $query->whereDate('tanggal', $date);
            } elseif ($type === 'monthly') {
                $query->whereMonth('tanggal', $date->month)->whereYear('tanggal', $date->year);
            } else { // semester
                $semesterMonthStart = $date->month <= 6 ? 1 : 7;
                $semesterMonthEnd = $date->month <= 6 ? 6 : 12;
                $query->whereBetween('tanggal', [
                    $date->copy()->month($semesterMonthStart)->startOfMonth()->format('Y-m-d'),
                    $date->copy()->month($semesterMonthEnd)->endOfMonth()->format('Y-m-d')
                ]);
            }
            $totalAntrian = $query->count();
            $completedAntrian = (clone $query)->where('status', 'completed')->count();
            $skippedAntrian = (clone $query)->where('status', 'skipped')->count();

            // Avg Rating
            $ratingQuery = Survei::query();
            if ($type === 'daily') {
                $ratingQuery->whereDate('created_at', $date);
            } elseif ($type === 'monthly') {
                $ratingQuery->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year);
            } else {
                $semesterMonthStart = $date->month <= 6 ? 1 : 7;
                $semesterMonthEnd = $date->month <= 6 ? 6 : 12;
                $ratingQuery->whereBetween('created_at', [
                    $date->copy()->month($semesterMonthStart)->startOfMonth(),
                    $date->copy()->month($semesterMonthEnd)->endOfMonth()
                ]);
            }
            $avgRating = $ratingQuery->avg('skor_kepuasan') ?? 0;

            fputcsv($file, ["Total Antrian Terdaftar", $totalAntrian]);
            fputcsv($file, ["Antrian Selesai Terlayani", $completedAntrian]);
            fputcsv($file, ["Antrian Dilewati/Batal", $skippedAntrian]);
            fputcsv($file, ["Indeks Kepuasan Masyarakat (SKM)", number_format($avgRating, 2) . " / 5.0"]);
            fputcsv($file, []);

            // Dinas Breakdown
            fputcsv($file, ["RINCIAN KINERJA PER GERAI DINAS"]);
            fputcsv($file, ["Kode Dinas", "Nama Dinas", "Total Antrian", "Selesai", "Dilewati", "Menunggu/Proses"]);

            $dinasBreakdown = DB::table('dinas')
                ->leftJoin('antrians', function($join) use ($type, $date) {
                    $join->on('dinas.id', '=', 'antrians.dinas_id');
                    if ($type === 'daily') {
                        $join->whereDate('antrians.tanggal', $date->format('Y-m-d'));
                    } elseif ($type === 'monthly') {
                        $join->whereMonth('antrians.tanggal', $date->month)->whereYear('antrians.tanggal', $date->year);
                    } else {
                        $semesterMonthStart = $date->month <= 6 ? 1 : 7;
                        $semesterMonthEnd = $date->month <= 6 ? 6 : 12;
                        $join->whereBetween('antrians.tanggal', [
                            $date->copy()->month($semesterMonthStart)->startOfMonth()->format('Y-m-d'),
                            $date->copy()->month($semesterMonthEnd)->endOfMonth()->format('Y-m-d')
                        ]);
                    }
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
}
