<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DetailProgramController extends Controller {
    public function show(int $id): View
    {
        $program = DB::table('program')
            ->where('id', $id)
            ->first();

        abort_if(!$program, 404, 'Program tidak ditemukan.');

        $details = DB::table('program_detail')
            ->where('program_id', $id)
            ->orderBy('id')
            ->get();

        $program->persyaratan = [];
        $program->benefit = [];
        $program->dokumen = [];

        foreach ($details as $detail) {
            $jenis = strtolower(trim($detail->jenis));

            if (in_array($jenis, ['persyaratan', 'benefit', 'dokumen'], true)) {
                $program->{$jenis}[] = $detail->isi;
            }
        }

        return view('detail-program.index', [
            'program' => $program,
        ]);
    }
}