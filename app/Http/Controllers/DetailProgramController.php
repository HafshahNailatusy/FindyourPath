<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DetailProgramController extends Controller {
    public function show(int $id): View
    {
        $program = null;

        if (Schema::hasTable('program')) {
            $program = DB::table('program')
                ->where('id', $id)
                ->first();
        }

        if (!$program && Schema::hasTable('program_management')) {
            $pm = DB::table('program_management')
                ->where('id_program', $id)
                ->first();

            if ($pm) {
                $gambar = $pm->poster ? 'uploads/posters/' . $pm->poster : null;

                $program = (object) [
                    'id'            => $pm->id_program,
                    'judul'         => $pm->nama_program,
                    'kategori'      => $pm->bidang ?? 'Umum',
                    'penyelenggara' => $pm->penyelenggara,
                    'lokasi'        => 'Online',
                    'tanggal_mulai' => '-',
                    'deadline'      => '-',
                    'gambar'        => $gambar,
                    'deskripsi'     => $pm->deskripsi,
                    'hadiah'        => '-',
                    'status'        => 'Dibuka',
                    'persyaratan'   => [],
                    'benefit'       => [],
                    'dokumen'       => [],
                ];
            }
        }

        abort_if(!$program, 404, 'Program tidak ditemukan.');

        if (!isset($program->persyaratan)) {
            $details = [];
            if (Schema::hasTable('program_detail')) {
                $details = DB::table('program_detail')
                    ->where('program_id', $id)
                    ->orderBy('id')
                    ->get();
            }

            $program->persyaratan = [];
            $program->benefit = [];
            $program->dokumen = [];

            foreach ($details as $detail) {
                $jenis = strtolower(trim($detail->jenis));

                if (in_array($jenis, ['persyaratan', 'benefit', 'dokumen'], true)) {
                    $program->{$jenis}[] = $detail->isi;
                }
            }
        }

        return view('detail-program.index', [
            'program' => $program,
        ]);
    }
}
