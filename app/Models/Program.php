<?php
class Program {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT *
             FROM program
             WHERE id = :id
             LIMIT 1"
        );

        $stmt->execute(['id' => $id]);
        $program = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$program) {
            return null;
        }

        $program['persyaratan'] = [];
        $program['benefit'] = [];
        $program['dokumen'] = [];

        $stmtDetail = $this->pdo->prepare(
            "SELECT jenis, isi
             FROM program_detail
             WHERE id_program = :id
             ORDER BY id ASC"
        );

        $stmtDetail->execute(['id' => $id]);
        $details = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

        foreach ($details as $detail) {
            $jenis = strtolower(trim($detail['jenis']));
            if (array_key_exists($jenis, $program)) {
                $program[$jenis][] = $detail['isi'];
            }
        }
        return $program;
    }
}