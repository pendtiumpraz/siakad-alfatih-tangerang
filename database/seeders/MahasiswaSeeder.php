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
            // Pendidikan Agama Islam (PAI)
            [
                'nim' => '2024010001',
                'nama_lengkap' => 'Ahmad Fauzi',
                'tempat_lahir' => 'Tangerang',
                'tanggal_lahir' => '2005-03-15',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. KH. Hasyim Ashari No. 12, Tangerang',
                'no_telepon' => '081234567811',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'PAI-S1-L')->first()?->id ?? $programStudis->first()->id,
            ],
            [
                'nim' => '2024010002',
                'nama_lengkap' => 'Fatimah Azzahra',
                'tempat_lahir' => 'Tangerang',
                'tanggal_lahir' => '2005-07-22',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Imam Bonjol No. 56, Tangerang',
                'no_telepon' => '081234567812',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'PAI-S1-D')->first()?->id ?? $programStudis->first()->id,
            ],
            // Manajemen Pendidikan Islam (MPI)
            [
                'nim' => '2024020001',
                'nama_lengkap' => 'Muhammad Rizki',
                'tempat_lahir' => 'Serang',
                'tanggal_lahir' => '2005-05-10',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Ahmad Yani No. 34, Serang',
                'no_telepon' => '081234567813',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'MPI-S1-L')->first()?->id ?? $programStudis->first()->id,
            ],
            [
                'nim' => '2024020002',
                'nama_lengkap' => 'Siti Aisyah',
                'tempat_lahir' => 'Cilegon',
                'tanggal_lahir' => '2005-09-18',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Sultan Agung No. 78, Cilegon',
                'no_telepon' => '081234567814',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'MPI-S1-D')->first()?->id ?? $programStudis->first()->id,
            ],
            // Hukum Ekonomi Syariah (HES)
            [
                'nim' => '2024030001',
                'nama_lengkap' => 'Abdurrahman Wahid',
                'tempat_lahir' => 'Tangerang',
                'tanggal_lahir' => '2005-12-05',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Diponegoro No. 90, Tangerang',
                'no_telepon' => '081234567815',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'HES-S1-L')->first()?->id ?? $programStudis->first()->id,
            ],
            // Pendidikan Guru Madrasah Ibtidaiyah (PGMI)
            [
                'nim' => '2024040001',
                'nama_lengkap' => 'Nur Halimah',
                'tempat_lahir' => 'Tangerang',
                'tanggal_lahir' => '2006-01-20',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Ki Hajar Dewantara No. 45, Tangerang',
                'no_telepon' => '081234567816',
                'angkatan' => 2024,
                'status' => 'aktif',
                'program_studi_id' => $programStudis->where('kode_prodi', 'PGMI-S1-D')->first()?->id ?? $programStudis->first()->id,
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
