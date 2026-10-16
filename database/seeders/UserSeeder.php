<?php

namespace Database\Seeders;

use App\Models\Poli;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $poliUmum = Poli::firstOrCreate(
            ['nama_poli' => 'Poli Umum'],
            ['keterangan' => 'Pelayanan pemeriksaan kesehatan umum dan pengobatan dasar bagi pasien.']
        );

        $poliGigi = Poli::firstOrCreate(
            ['nama_poli' => 'Poli Gigi'],
            ['keterangan' => 'Pelayanan perawatan, penambalan, pencabutan, dan kebersihan kesehatan gigi dan mulut.']
        );

        $poliAnak = Poli::firstOrCreate(
            ['nama_poli' => 'Poli Anak'],
            ['keterangan' => 'Pelayanan kesehatan medis, imunisasi, dan pemantauan tumbuh kembang anak.']
        );

        $users = [
            [
                'email' => 'admin@gmail.com',
                'data' => [
                    'nama' => 'Admin Sistem',
                    'alamat' => 'Semarang',
                    'no_hp' => '081200000001',
                    'password' => Hash::make('admin'),
                    'role' => 'admin',
                ]
            ],
            [
                'email' => 'dokter@gmail.com',
                'data' => [
                    'nama' => 'dr. Hendra Pratama',
                    'alamat' => 'Jl. Pandanaran No. 10, Semarang',
                    'no_ktp' => '3301011101800001',
                    'no_hp' => '081234567801',
                    'password' => Hash::make('dokter'),
                    'role' => 'dokter',
                    'id_poli' => $poliUmum->id,
                ]
            ],
            [
                'email' => 'dokter.budi@gmail.com',
                'data' => [
                    'nama' => 'dr. Budi Santoso, Sp.PD',
                    'alamat' => 'Jl. Pemuda No. 45, Semarang',
                    'no_ktp' => '3301012304850002',
                    'no_hp' => '081234567802',
                    'password' => Hash::make('dokter123'),
                    'role' => 'dokter',
                    'id_poli' => $poliUmum->id,
                ]
            ],
            [
                'email' => 'dokter.siti@gmail.com',
                'data' => [
                    'nama' => 'drg. Siti Rahmawati, Sp.Ort',
                    'alamat' => 'Jl. Pahlawan No. 22, Semarang',
                    'no_ktp' => '3301015607900003',
                    'no_hp' => '081234567803',
                    'password' => Hash::make('dokter123'),
                    'role' => 'dokter',
                    'id_poli' => $poliGigi->id,
                ]
            ],
            [
                'email' => 'pasien@gmail.com',
                'data' => [
                    'nama' => 'Pasien Umum',
                    'alamat' => 'Jl. MT Haryono No. 5, Semarang',
                    'no_ktp' => '3301011112950001',
                    'no_hp' => '085600000001',
                    'no_rm' => '202610-001',
                    'password' => Hash::make('pasien'),
                    'role' => 'pasien',
                ]
            ],
            [
                'email' => 'bambang.wijaya@gmail.com',
                'data' => [
                    'nama' => 'Bambang Wijaya',
                    'alamat' => 'Jl. Gajah Mada No. 12, Semarang',
                    'no_ktp' => '3301011203950002',
                    'no_hp' => '085612345672',
                    'no_rm' => '202610-002',
                    'password' => Hash::make('pasien123'),
                    'role' => 'pasien',
                ]
            ],
            [
                'email' => 'dewi.lestari@gmail.com',
                'data' => [
                    'nama' => 'Dewi Lestari',
                    'alamat' => 'Jl. Diponegoro No. 34, Semarang',
                    'no_ktp' => '3301016508980003',
                    'no_hp' => '085612345673',
                    'no_rm' => '202610-003',
                    'password' => Hash::make('pasien123'),
                    'role' => 'pasien',
                ]
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user['data']
            );
        }
    }
}