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
            // Mahasiswa 1 - PAI
            [
                'username' => 'mhs001',
                'email' => 'ahmad.fauzi@student.staialfatih.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
            // Mahasiswa 2 - PAI
            [
                'username' => 'mhs002',
                'email' => 'fatimah.azzahra@student.staialfatih.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
            // Mahasiswa 3 - MPI
            [
                'username' => 'mhs003',
                'email' => 'muhammad.rizki@student.staialfatih.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
            // Mahasiswa 4 - MPI
            [
                'username' => 'mhs004',
                'email' => 'siti.aisyah@student.staialfatih.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
            // Mahasiswa 5 - HES
            [
                'username' => 'mhs005',
                'email' => 'abdurrahman.wahid@student.staialfatih.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ],
            // Mahasiswa 6 - PGMI
            [
                'username' => 'mhs006',
                'email' => 'nur.halimah@student.staialfatih.ac.id',
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
