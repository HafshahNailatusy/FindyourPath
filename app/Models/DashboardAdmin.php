<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardAdmin
{
    public function getTotalPrograms(): int
    {
        if (!Schema::hasTable('program_management')) {
            return 0;
        }
        return (int) DB::table('program_management')->count('id_program');
    }

    public function getProgramGrowthPercentage(): int
    {
        if (!Schema::hasTable('program_management') || !Schema::hasColumn('program_management', 'created_at')) {
            return 0;
        }

        $now = Carbon::now();
        $thisMonth = DB::table('program_management')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count('id_program');

        $lastMonthDate = $now->copy()->subMonth();
        $lastMonth = DB::table('program_management')
            ->whereYear('created_at', $lastMonthDate->year)
            ->whereMonth('created_at', $lastMonthDate->month)
            ->count('id_program');

        return $this->calculateGrowth($thisMonth, $lastMonth);
    }

    public function getTotalSchedules(): int
    {
        if (!Schema::hasTable('schedules_management')) {
            return 0;
        }
        return (int) DB::table('schedules_management')->count('id_schedule');
    }

    public function getScheduleGrowthPercentage(): int
    {
        if (!Schema::hasTable('schedules_management') || !Schema::hasColumn('schedules_management', 'created_at')) {
            return 0;
        }

        $now = Carbon::now();
        $thisMonth = DB::table('schedules_management')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count('id_schedule');

        $lastMonthDate = $now->copy()->subMonth();
        $lastMonth = DB::table('schedules_management')
            ->whereYear('created_at', $lastMonthDate->year)
            ->whereMonth('created_at', $lastMonthDate->month)
            ->count('id_schedule');

        return $this->calculateGrowth($thisMonth, $lastMonth);
    }

    public function getNearestScheduleReminder(): array
    {
        if (!Schema::hasTable('schedules_management')) {
            return [
                'badgeText'  => '-',
                'label'      => 'Belum ada jadwal terdekat',
                'extraCount' => 0,
                'fullList'   => 'Belum ada jadwal terdekat',
                'isUrgent'   => false,
            ];
        }

        $today = Carbon::today();
        $hasProgramTable = Schema::hasTable('program_management');

        $query = DB::table('schedules_management as s');
        if ($hasProgramTable) {
            $query->leftJoin('program_management as p', 's.id_program', '=', 'p.id_program')
                  ->select('s.*', 'p.nama_program');
        } else {
            $query->select('s.*');
        }

        $schedules = $query->get();
        $candidates = [];

        foreach ($schedules as $row) {
            $programName = $row->nama_program ?? 'Program #' . $row->id_program;
            $stageName   = !empty($row->stage_name) ? $row->stage_name : 'Tahap Program';
            $scheduleId  = (int) ($row->id_schedule ?? 0);

            $dateChecks = [
                ['col' => 'end_date',   'priority' => 1, 'label' => 'Tutup ' . $stageName . ' ' . $programName],
                ['col' => 'event_date', 'priority' => 2, 'label' => 'Pelaksanaan ' . $stageName . ' ' . $programName],
                ['col' => 'start_date', 'priority' => 3, 'label' => 'Mulai ' . $stageName . ' ' . $programName],
            ];

            foreach ($dateChecks as $check) {
                $col = $check['col'];
                if (!empty($row->$col)) {
                    $targetDate = Carbon::parse($row->$col)->startOfDay();
                    if ($targetDate->gte($today)) {
                        $diffDays = (int) $today->diffInDays($targetDate);
                        $candidates[] = [
                            'diffDays'   => $diffDays,
                            'priority'   => $check['priority'],
                            'scheduleId' => $scheduleId,
                            'label'      => $check['label'],
                        ];
                    }
                }
            }
        }

        if (empty($candidates)) {
            return [
                'badgeText'  => '-',
                'label'      => 'Tidak ada agenda mendatang',
                'extraCount' => 0,
                'fullList'   => 'Tidak ada agenda mendatang',
                'isUrgent'   => false,
            ];
        }

        usort($candidates, function ($a, $b) {
            return [$a['diffDays'], $a['priority'], $a['scheduleId']]
                <=> [$b['diffDays'], $b['priority'], $b['scheduleId']];
        });

        $nearest = $candidates[0];
        $sameDayItems = array_values(array_filter(
            $candidates,
            fn ($item) => $item['diffDays'] === $nearest['diffDays']
        ));

        $extraCount = count($sameDayItems) - 1;
        $fullList = implode('; ', array_map(fn ($item) => $item['label'], $sameDayItems));

        $badgeText = match (true) {
            $nearest['diffDays'] === 0 => 'Hari Ini',
            $nearest['diffDays'] === 1 => 'H-1 (Besok)',
            default                    => 'H-' . $nearest['diffDays'],
        };

        return [
            'badgeText'  => $badgeText,
            'label'      => $nearest['label'],
            'extraCount' => $extraCount,
            'fullList'   => $fullList,
            'isUrgent'   => $nearest['diffDays'] <= 2,
        ];
    }

    public function getTotalActiveStudents(): int
    {
        if (!Schema::hasTable('students')) {
            return 0;
        }
        return (int) DB::table('students')->count('id');
    }

    public function getStudentGrowthPercentage(): int
    {
        if (!Schema::hasTable('students')) {
            return 0;
        }

        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        if (Schema::hasColumn('students', 'created_at')) {
            $current = DB::table('students')
                ->whereRaw('MONTH(COALESCE(created_at, NOW())) = ?', [$now->month])
                ->whereRaw('YEAR(COALESCE(created_at, NOW())) = ?', [$now->year])
                ->count('id');

            $previous = DB::table('students')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->count('id');
        } elseif (Schema::hasTable('users') && Schema::hasColumn('users', 'created_at')) {
            $current = DB::table('students')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->whereRaw('MONTH(COALESCE(users.created_at, NOW())) = ?', [$now->month])
                ->whereRaw('YEAR(COALESCE(users.created_at, NOW())) = ?', [$now->year])
                ->count('students.id');

            $previous = DB::table('students')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->whereMonth('users.created_at', $lastMonth->month)
                ->whereYear('users.created_at', $lastMonth->year)
                ->count('students.id');
        } else {
            $current = DB::table('students')->count('id');
            $previous = 0;
        }

        return $this->calculateGrowth($current, $previous);
    }

    public function getAvailableYears(): array
    {
        $currentYear = (int) Carbon::now()->year;
        $years = range(2022, $currentYear + 1);

        if (Schema::hasTable('students')) {
            if (Schema::hasColumn('students', 'created_at')) {
                $dbYears = DB::table('students')
                    ->selectRaw('DISTINCT YEAR(COALESCE(created_at, NOW())) as yr')
                    ->pluck('yr')
                    ->toArray();
                $years = array_merge($years, $dbYears);
            } elseif (Schema::hasTable('users') && Schema::hasColumn('users', 'created_at')) {
                $dbYears = DB::table('students')
                    ->join('users', 'students.user_id', '=', 'users.id')
                    ->selectRaw('DISTINCT YEAR(COALESCE(users.created_at, NOW())) as yr')
                    ->pluck('yr')
                    ->toArray();
                $years = array_merge($years, $dbYears);
            }
        }

        $years = array_unique(array_map('intval', $years));
        rsort($years);

        return $years;
    }

    public function getMonthlyStudentTrends(?int $year = null): array
    {
        $selectedYear = $year ?? (int) Carbon::now()->year;
        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $result = [];
        foreach ($namaBulan as $num => $label) {
            $result[$label] = 0;
        }

        if (Schema::hasTable('students')) {
            if (Schema::hasColumn('students', 'created_at')) {
                $rows = DB::table('students')
                    ->selectRaw('MONTH(COALESCE(created_at, NOW())) as bln, COUNT(id) as total')
                    ->whereRaw('YEAR(COALESCE(created_at, NOW())) = ?', [$selectedYear])
                    ->groupBy('bln')
                    ->get();
            } elseif (Schema::hasTable('users') && Schema::hasColumn('users', 'created_at')) {
                $rows = DB::table('students')
                    ->join('users', 'students.user_id', '=', 'users.id')
                    ->selectRaw('MONTH(COALESCE(users.created_at, NOW())) as bln, COUNT(students.id) as total')
                    ->whereRaw('YEAR(COALESCE(users.created_at, NOW())) = ?', [$selectedYear])
                    ->groupBy('bln')
                    ->get();
            } else {
                $rows = collect();
            }

            foreach ($rows as $row) {
                $blnInt = (int) $row->bln;
                if (isset($namaBulan[$blnInt])) {
                    $result[$namaBulan[$blnInt]] = (int) $row->total;
                }
            }
        }

        return $result;
    }

    public function getProgramDistributionByField(): array
    {
        if (!Schema::hasTable('program_management') || !Schema::hasColumn('program_management', 'bidang')) {
            return [
                'items'      => [],
                'grandTotal' => 0,
            ];
        }

        $rows = DB::table('program_management')
            ->select('bidang', DB::raw('COUNT(id_program) as total'))
            ->whereNotNull('bidang')
            ->where('bidang', '!=', '')
            ->groupBy('bidang')
            ->orderByDesc('total')
            ->get();

        $totalAll = $rows->sum('total');
        $colors = ['#4EACD4', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA', '#F87171'];
        $items = [];

        foreach ($rows as $index => $row) {
            $count = (int) $row->total;
            $pct = $totalAll > 0 ? (int) round(($count / $totalAll) * 100) : 0;

            $items[] = [
                'label' => $row->bidang,
                'total' => $count,
                'pct'   => $pct,
                'color' => $colors[$index % count($colors)],
            ];
        }

        return [
            'items'      => $items,
            'grandTotal' => (int) $totalAll,
        ];
    }

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