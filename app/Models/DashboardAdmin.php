<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardAdmin
{
    /**
     * Kartu 1: Total Program dari tabel `program` (berdasarkan id_program)
     */
    public function getTotalPrograms(): int
    {
        if (!Schema::hasTable('program')) {
            return 0;
        }
        return (int) DB::table('program')->count('id_program');
    }

    public function getProgramGrowthPercentage(): int
    {
        if (!Schema::hasTable('program') || !Schema::hasColumn('program', 'created_at')) {
            return 0;
        }

        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $current = DB::table('program')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $previous = DB::table('program')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        return $this->calculateGrowth($current, $previous);
    }

    /**
     * Kartu 4: Total Mahasiswa (terhubung ke id_mahasiswa yang terdaftar/login di FindYourPath)
     */
    public function getTotalActiveStudents(): int
    {
        if (!Schema::hasTable('mahasiswa')) {
            return 0;
        }
        $pk = Schema::hasColumn('mahasiswa', 'id_mahasiswa') ? 'id_mahasiswa' : 'id';
        return (int) DB::table('mahasiswa')->count($pk);
    }

    public function getStudentGrowthPercentage(): int
    {
        if (!Schema::hasTable('mahasiswa') || !Schema::hasColumn('mahasiswa', 'created_at')) {
            return 0;
        }

        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $current = DB::table('mahasiswa')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $previous = DB::table('mahasiswa')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        return $this->calculateGrowth($current, $previous);
    }

    /**
     * Grafik Line: Tren Mahasiswa (5 bulan terakhir) dari tabel `mahasiswa`
     */
    public function getMonthlyStudentTrends(): array
    {
        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $trends = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $ym = $date->format('Y-m');
            $trends[$ym] = [
                'label' => $namaBulan[(int) $date->format('n')],
                'total' => 0,
            ];
        }

        if (Schema::hasTable('mahasiswa') && Schema::hasColumn('mahasiswa', 'created_at')) {
            $startDate = Carbon::now()->subMonths(4)->startOfMonth();
            $rows = DB::table('mahasiswa')
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as total")
                ->where('created_at', '>=', $startDate)
                ->groupBy('ym')
                ->get();

            foreach ($rows as $row) {
                if (isset($trends[$row->ym])) {
                    $trends[$row->ym]['total'] = (int) $row->total;
                }
            }
        }

        $result = [];
        foreach ($trends as $t) {
            $result[$t['label']] = $t['total'];
        }

        return $result;
    }

    /**
     * Donut Chart: Distribusi Program berdasarkan kolom `bidang` pada tabel `program`
     */
    public function getProgramDistributionByField(): array
    {
        $data = [];
        if (Schema::hasTable('program') && Schema::hasColumn('program', 'bidang')) {
            $rows = DB::table('program')
                ->select('bidang as label', DB::raw('COUNT(*) as total'))
                ->whereNotNull('bidang')
                ->where('bidang', '!=', '')
                ->groupBy('bidang')
                ->orderByDesc('total')
                ->get();

            foreach ($rows as $r) {
                $data[] = [
                    'label' => $r->label,
                    'total' => (int) $r->total,
                ];
            }
        }

        $grandTotal = array_sum(array_column($data, 'total'));
        $colors = ['#4EACD4', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA'];

        foreach ($data as $index => &$item) {
            $item['pct']   = $grandTotal > 0 ? round(($item['total'] / $grandTotal) * 100) : 0;
            $item['color'] = $colors[$index % count($colors)];
        }

        return [
            'items'      => $data,
            'grandTotal' => $grandTotal,
        ];
    }

    /**
     * Tabel Bawah: 5 Pengumuman Terbaru dari tabel `announcements`
     */
    public function getRecentAnnouncements(int $limit = 5): array
    {
        if (!Schema::hasTable('announcements')) {
            return [];
        }

        $rows = Announcement::orderByDesc('created_at')
            ->limit($limit)
            ->get();

        $bulanIndo = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $formatted = [];
        foreach ($rows as $row) {
            $ts = $row->created_at ? Carbon::parse($row->created_at) : Carbon::now();
            $tgl = $ts->format('d') . ' ' . $bulanIndo[(int) $ts->format('n')] . ' ' . $ts->format('Y, H:i');

            $badgeClass = match ($row->category) {
                'Penting'       => 'status-badge--rejected',
                'Jadwal'        => 'status-badge--pending',
                'Hasil Seleksi' => 'status-badge--approved',
                default         => 'program-badge',
            };

            $formatted[] = [
                'id'       => $row->id_announcement,
                'title'    => $row->title,
                'content'  => $row->content,
                'category' => $row->category,
                'date'     => $tgl,
                'badge'    => $badgeClass,
            ];
        }

        return $formatted;
    }

    private function calculateGrowth(int $current, int $previous): int
    {
        if ($previous <= 0) {
            return $current > 0 ? 100 : 0;
        }
        return (int) round((($current - $previous) / $previous) * 100);
    }
}