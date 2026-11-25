<?php

namespace App\Http\Controllers;

use App\Models\DashboardAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
{
    protected DashboardAdmin $model;

    public function __construct()
    {
        $this->model = new DashboardAdmin();
    }

    public function index(Request $request)
    {
        // Kartu 1: Total Program (id_program)
        $totalProgram  = $this->model->getTotalPrograms();
        $programGrowth = $this->model->getProgramGrowthPercentage();

        // Kartu 2: Total Schedule (id_schedule)
        $totalSchedule  = $this->model->getTotalSchedules();
        $scheduleGrowth = $this->model->getScheduleGrowthPercentage();

        // Kartu 3: Radar Agenda / Deadline Terdekat (pengingat buat admin)
        $nearestSchedule = $this->model->getNearestScheduleReminder();

        // Kartu 4: Total Mahasiswa Aktif (terhubung ke students.id)
        $totalMahasiswa  = $this->model->getTotalActiveStudents();
        $mahasiswaGrowth = $this->model->getStudentGrowthPercentage();

        // Filter Tahun & Grafik Line 12 Bulan
        $availableYears = $this->model->getAvailableYears();
        $selectedYear   = (int) $request->query('year', date('Y'));
        if (!in_array($selectedYear, $availableYears)) {
            $availableYears[] = $selectedYear;
            rsort($availableYears);
        }

        $monthlyTrends = $this->model->getMonthlyStudentTrends($selectedYear);
        $maxTrendVal   = !empty($monthlyTrends) ? max($monthlyTrends) : 0;
        $chartMaxY     = $maxTrendVal > 0 ? (int) (ceil($maxTrendVal / 4) * 4) : 4;

        // Donut Chart: Distribusi Program berdasarkan Bidang
        $distribusi         = $this->model->getProgramDistributionByField();
        $bidangPrograms     = $distribusi['items'];
        $totalBidangProgram = $distribusi['grandTotal'];

        $recentAnnouncements = $this->model->getRecentAnnouncements(5);

        $activeMenu = 'dashboard';
        $adminName  = Auth::user()?->name ?? session('user.name', 'Admin');

        return response()
            ->view('admin.dashboard-admin.index', compact(
                'totalProgram',
                'programGrowth',
                'totalSchedule',
                'scheduleGrowth',
                'nearestSchedule',
                'totalMahasiswa',
                'mahasiswaGrowth',
                'availableYears',
                'selectedYear',
                'monthlyTrends',
                'chartMaxY',
                'bidangPrograms',
                'totalBidangProgram',
                'recentAnnouncements',
                'activeMenu',
                'adminName'
            ))
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ]);
    }
}
