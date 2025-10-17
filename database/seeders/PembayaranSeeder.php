<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Operator;
use App\Models\Pembayaran;
use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semester = Semester::where('is_active', true)->first();
        $mahasiswas = Mahasiswa::all();
        $operator = Operator::first();

        $jenisPembayaran = ['spp', 'daftar_ulang', 'wisuda', 'lainnya'];
        $statusOptions = ['lunas', 'pending', 'lunas'];

        foreach ($mahasiswas as $index => $mahasiswa) {
            // Create 2-3 payments per student
            $numPayments = rand(2, 3);

            for ($i = 0; $i < $numPayments; $i++) {
                $jenis = $jenisPembayaran[$i % count($jenisPembayaran)];
                $status = $statusOptions[($index + $i) % count($statusOptions)];

                // Determine amount based on payment type
                $jumlah = match ($jenis) {
                    'spp' => 3000000,
                    'daftar_ulang' => 2000000,
                    'wisuda' => 1500000,
                    'lainnya' => 500000,
                    default => 1000000,
                };

                // Set due date (jatuh tempo) and payment date
                $tanggalJatuhTempo = now()->subDays(rand(40, 60));
                $tanggalBayar = $status === 'lunas'
                    ? now()->subDays(rand(1, 30))
                    : null;

                Pembayaran::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'semester_id' => $semester->id,
                    'operator_id' => $status === 'lunas' ? $operator->id : null,
                    'jenis_pembayaran' => $jenis,
                    'jumlah' => $jumlah,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                    'tanggal_bayar' => $tanggalBayar,
                    'status' => $status,
                    'keterangan' => $status === 'lunas'
                        ? "Pembayaran {$jenis} semester {$semester->nama_semester}"
                        : "Menunggu pembayaran {$jenis}",
                ]);
            }
        }
    }
}
