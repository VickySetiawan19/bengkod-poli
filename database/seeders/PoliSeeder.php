<?php

namespace Database\Seeders;

use App\Models\Poli;
use Illuminate\Database\Seeder;

class PoliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $polis = [
            [
                'nama_poli' => 'Poli Umum',
                'keterangan' => 'Pelayanan pemeriksaan kesehatan umum dan pengobatan dasar bagi pasien.',
            ],
            [
                'nama_poli' => 'Poli Gigi',
                'keterangan' => 'Pelayanan perawatan, penambalan, pencabutan, dan kebersihan kesehatan gigi dan mulut.',
            ],
            [
                'nama_poli' => 'Poli Anak',
                'keterangan' => 'Pelayanan kesehatan medis, imunisasi, dan pemantauan tumbuh kembang anak.',
            ],
        ];

        foreach ($polis as $poli) {
            Poli::create($poli);
        }
    }
}
