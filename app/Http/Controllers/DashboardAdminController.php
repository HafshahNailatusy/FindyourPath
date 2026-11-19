<?php

namespace App\Http\Controllers;

use App\Models\DashboardAdmin;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    protected DashboardAdmin $model;

    public function __construct()
    {
        $this->model = new DashboardAdmin();
    }

    public function index()
    {
        // Kartu 1: Total Program (id_program)
        $totalProgram  = $this->model->getTotalPrograms();
        $programGrowth = $this->model->getProgramGrowthPercentage();

        // Kartu 2 & 3: Total Lomba & Total Beasiswa dikosongkan sementara sesuai permintaan
        $totalLomba     = 0;
        $lombaGrowth    = 0;
        $totalBeasiswa  = 0;
        $beasiswaGrowth = 0;

        // Kartu 4: Total Mahasiswa Aktif (terhubung ke id_mahasiswa)
        $totalMahasiswa  = $this->model->getTotalActiveStudents();
        $mahasiswaGrowth = $this->model->getStudentGrowthPercentage();

        // Grafik Line: Tren Mahasiswa 5 Bulan Terakhir
        $monthlyTrends = $this->model->getMonthlyStudentTrends();
        $maxTrendVal   = !empty($monthlyTrends) ? max($monthlyTrends) : 0;
        $chartMaxY     = $maxTrendVal > 0 ? (int) (ceil($maxTrendVal / 4) * 4) : 4;

        // Donut Chart: Distribusi Program berdasarkan Bidang
        $distribusi        = $this->model->getProgramDistributionByField();
        $bidangPrograms    = $distribusi['items'];
        $totalBidangProgram = $distribusi['grandTotal'];

        // Tabel Bawah: Pengumuman Terbaru
        $recentAnnouncements = $this->model->getRecentAnnouncements(5);

        $activeMenu = 'dashboard';
        $adminName  = auth()->user()->name ?? session('user.name', 'Admin');

        return view('admin.dashboard-admin.index', compact(
            'totalProgram',
            'programGrowth',
            'totalLomba',
            'lombaGrowth',
            'totalBeasiswa',
            'beasiswaGrowth',
            'totalMahasiswa',
            'mahasiswaGrowth',
            'monthlyTrends',
            'chartMaxY',
            'bidangPrograms',
            'totalBidangProgram',
            'recentAnnouncements',
            'activeMenu',
            'adminName'
        ));
    }
}