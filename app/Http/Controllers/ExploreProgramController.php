<?php

namespace App\Http\Controllers;

use App\Models\ProgramManagement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ExploreProgramController extends Controller
{
    public function index(): View
    {
        $programs = collect();

        if (Schema::hasTable('program')) {
            $dbPrograms = DB::table('program')->orderBy('id', 'asc')->get();

            foreach ($dbPrograms as $p) {
                $img = $p->gambar ?? $p->image ?? null;
                if ($img && !str_starts_with($img, 'http://') && !str_starts_with($img, 'https://')) {
                    $img = asset($img);
                }

                $programs->push((object) [
                    'id'            => $p->id,
                    'nama_program'  => $p->judul ?? $p->nama_program ?? 'Program',
                    'penyelenggara' => $p->penyelenggara ?? '-',
                    'bidang'        => $p->kategori ?? $p->bidang ?? 'Umum',
                    'deskripsi'     => $p->deskripsi ?? '',
                    'poster'        => $img,
                ]);
            }
        }

        if (Schema::hasTable('program_management')) {
            $mgmtPrograms = ProgramManagement::orderBy('id_program', 'desc')->get();

            foreach ($mgmtPrograms as $pm) {
                if (!$programs->contains('nama_program', $pm->nama_program)) {
                    $img = null;
                    if ($pm->poster) {
                        $img = asset('uploads/posters/' . $pm->poster);
                    }

                    $programs->push((object) [
                        'id'            => $pm->id_program,
                        'nama_program'  => $pm->nama_program,
                        'penyelenggara' => $pm->penyelenggara,
                        'bidang'        => $pm->bidang ?? 'Umum',
                        'deskripsi'     => $pm->deskripsi ?? '',
                        'poster'        => $img,
                    ]);
                }
            }
        }

        return view('explore.index', [
            'programs' => $programs,
        ]);
    }
}

