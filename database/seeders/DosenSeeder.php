<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosenUsers = User::where('role', 'dosen')->get();

        $dosenData = [
            [
                'nidn' => '0301108901',
                'nama_lengkap' => 'Ahmad Fauzi',
                'gelar_depan' => 'Dr.',
                'gelar_belakang' => 'M.Kom',
                'no_telepon' => '081234567801',
                'email_dosen' => 'ahmad.fauzi@siakad.ac.id',
            ],
            [
                'nidn' => '0415108802',
                'nama_lengkap' => 'Siti Nurhaliza',
                'gelar_depan' => null,
                'gelar_belakang' => 'M.T',
                'no_telepon' => '081234567802',
                'email_dosen' => 'siti.nurhaliza@siakad.ac.id',
            ],
            [
                'nidn' => '0520079001',
                'nama_lengkap' => 'Budi Santoso',
                'gelar_depan' => null,
                'gelar_belakang' => 'M.Kom',
                'no_telepon' => '081234567803',
                'email_dosen' => 'budi.santoso@siakad.ac.id',
            ],
        ];

        foreach ($dosenUsers as $index => $user) {
            if (isset($dosenData[$index])) {
                Dosen::create(array_merge($dosenData[$index], [
                    'user_id' => $user->id,
                ]));
            }
        }
    }
}
