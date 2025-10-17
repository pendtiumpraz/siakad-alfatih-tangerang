<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswaUsers = User::where('role', 'mahasiswa')->get();
        $programStudis = ProgramStudi::all();

        $mahasiswaData = [
            [
                'nim' => '2024010001',
                'nama_lengkap' => 'Andi Pratama',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2005-03-15',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Kebon Jeruk No. 12, Jakarta Barat',
                'no_telepon' => '081234567811',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'TI')->first()->id,
            ],
            [
                'nim' => '2024010002',
                'nama_lengkap' => 'Dewi Lestari',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2005-07-22',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Dago No. 56, Bandung',
                'no_telepon' => '081234567812',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'TI')->first()->id,
            ],
            [
                'nim' => '2024020001',
                'nama_lengkap' => 'Rizky Ramadan',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2005-05-10',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Basuki Rahmat No. 34, Surabaya',
                'no_telepon' => '081234567813',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'SI')->first()->id,
            ],
            [
                'nim' => '2024020002',
                'nama_lengkap' => 'Maya Sari',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2005-09-18',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Malioboro No. 78, Yogyakarta',
                'no_telepon' => '081234567814',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'SI')->first()->id,
            ],
            [
                'nim' => '2024030001',
                'nama_lengkap' => 'Fajar Nugraha',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '2005-12-05',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Pemuda No. 90, Semarang',
                'no_telepon' => '081234567815',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'MI')->first()->id,
            ],
        ];

        foreach ($mahasiswaUsers as $index => $user) {
            if (isset($mahasiswaData[$index])) {
                Mahasiswa::create(array_merge($mahasiswaData[$index], [
                    'user_id' => $user->id,
                ]));
            }
        }
    }
}
