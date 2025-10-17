<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Super Admin
            [
                'username' => 'superadmin',
                'email' => 'superadmin@siakad.ac.id',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_active' => true,
            ],
            // Operator
            [
                'username' => 'operator',
                'email' => 'operator@siakad.ac.id',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'is_active' => true,
            ],
            // Dosen 1
            [
                'username' => 'dosen001',
                'email' => 'ahmad.fauzi@siakad.ac.id',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'is_active' => true,
            ],
            // Dosen 2
            [
                'username' => 'dosen002',
                'email' => 'siti.nurhaliza@siakad.ac.id',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'is_active' => true,
            ],
            // Dosen 3
            [
                'username' => 'dosen003',
                'email' => 'budi.santoso@siakad.ac.id',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'is_active' => true,
            ],
            // Mahasiswa 1
            [
                'username' => 'mhs001',
                'email' => 'andi.pratama@student.siakad.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
            // Mahasiswa 2
            [
                'username' => 'mhs002',
                'email' => 'dewi.lestari@student.siakad.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
            // Mahasiswa 3
            [
                'username' => 'mhs003',
                'email' => 'rizky.ramadan@student.siakad.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
            // Mahasiswa 4
            [
                'username' => 'mhs004',
                'email' => 'maya.sari@student.siakad.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
            // Mahasiswa 5
            [
                'username' => 'mhs005',
                'email' => 'fajar.nugraha@student.siakad.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
