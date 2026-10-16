<?php

namespace Database\Seeders;

use App\Models\Obat;
use Illuminate\Database\Seeder;

class ObatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $obats = [
            [
                'nama_obat' => 'Paracetamol 500 mg',
                'kemasan' => 'Strip @ 10 tablet',
                'harga' => 10000,
            ],
            [
                'nama_obat' => 'Amoxicillin 500 mg',
                'kemasan' => 'Strip @ 10 kapsul',
                'harga' => 15000,
            ],
            [
                'nama_obat' => 'Antasida Doen',
                'kemasan' => 'Botol 60 ml',
                'harga' => 12000,
            ],
            [
                'nama_obat' => 'Cetirizine 10 mg',
                'kemasan' => 'Strip @ 10 tablet',
                'harga' => 18000,
            ],
            [
                'nama_obat' => 'OBH Sirup Batuk',
                'kemasan' => 'Botol 100 ml',
                'harga' => 22000,
            ],
        ];

        foreach ($obats as $obat) {
            Obat::create($obat);
        }
    }
}
