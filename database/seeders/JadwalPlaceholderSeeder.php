<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Models\Dosen;
use App\Models\Ruangan;
use Illuminate\Support\Facades\DB;

class JadwalPlaceholderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Create placeholder jadwal for all mata kuliah
     * Admin can edit later via UI
     */
    public function run(): void
    {
        echo "🔄 Starting Jadwal Placeholder Seeder...\n\n";

        // Get active semester
        $semester = Semester::where('is_active', true)->first();
        
        if (!$semester) {
            echo "❌ ERROR: No active semester found! Please activate a semester first.\n";
            return;
        }

        echo "✓ Active Semester: {$semester->nama_semester} {$semester->tahun_akademik}\n";

        // Check or create dummy dosen
        $dummyDosen = Dosen::where('nidn', '0000000000')->first();
        
        if (!$dummyDosen) {
            echo "⚠️  Creating dummy user & dosen...\n";
            
            // Create dummy user first
            $dummyUser = \App\Models\User::create([
                'username' => 'placeholder_dosen',
                'email' => 'placeholder.dosen@example.com',
                'password' => bcrypt('placeholder123'),
                'role' => 'dosen',
                'is_active' => false, // Inactive placeholder
            ]);
            
            $dummyDosen = Dosen::create([
                'user_id' => $dummyUser->id,
                'nidn' => '0000000000',
                'nama_lengkap' => 'Belum Ditentukan',
                'email_dosen' => 'placeholder.dosen@example.com',
                'no_telepon' => '000000000000',
            ]);
            echo "✓ Dummy Dosen created (ID: {$dummyDosen->id})\n";
        } else {
            echo "✓ Dummy Dosen exists (ID: {$dummyDosen->id})\n";
        }

        // Check or create dummy ruangan
        $dummyRuangan = Ruangan::where('nama_ruangan', 'Belum Ditentukan')->first();
        
        if (!$dummyRuangan) {
            echo "⚠️  Creating dummy ruangan...\n";
            $dummyRuangan = Ruangan::create([
                'kode_ruangan' => 'TBD',
                'nama_ruangan' => 'Belum Ditentukan',
                'kapasitas' => 0,
            ]);
            echo "✓ Dummy Ruangan created (ID: {$dummyRuangan->id})\n";
        } else {
            echo "✓ Dummy Ruangan exists (ID: {$dummyRuangan->id})\n";
        }

        // Get all mata kuliah
        $mataKuliahs = MataKuliah::all();
        
        if ($mataKuliahs->count() == 0) {
            echo "❌ ERROR: No mata kuliah found in database!\n";
            return;
        }

        echo "\n📚 Found {$mataKuliahs->count()} mata kuliah\n";
        echo "🔄 Creating placeholder jadwal...\n\n";

        $created = 0;
        $skipped = 0;

        DB::beginTransaction();

        try {
            foreach ($mataKuliahs as $mk) {
                // Check if jadwal already exists for this MK in this semester
                $exists = Jadwal::where('semester_id', $semester->id)
                    ->where('mata_kuliah_id', $mk->id)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                // Create placeholder jadwal
                Jadwal::create([
                    'semester_id' => $semester->id,
                    'mata_kuliah_id' => $mk->id,
                    'dosen_id' => $dummyDosen->id,
                    'ruangan_id' => $dummyRuangan->id,
                    'hari' => 'Senin', // Default, admin will change
                    'jam_mulai' => '00:00:00', // Placeholder = belum dijadwalkan
                    'jam_selesai' => '00:00:00',
                    'kelas' => 'A', // Default
                ]);

                $created++;

                if ($created % 10 == 0) {
                    echo "   Created {$created} jadwal...\n";
                }
            }

            DB::commit();

            echo "\n✅ SUCCESS!\n";
            echo "   Created: {$created} jadwal placeholder\n";
            echo "   Skipped: {$skipped} jadwal (already exists)\n";
            echo "\n📝 NOTE:\n";
            echo "   - All jadwal created with placeholder data\n";
            echo "   - Dosen: Belum Ditentukan\n";
            echo "   - Ruangan: Belum Ditentukan\n";
            echo "   - Jam: 00:00:00 (belum dijadwalkan)\n";
            echo "   - Hari: Senin (default)\n";
            echo "\n🔧 NEXT STEP:\n";
            echo "   Admin harus edit jadwal via menu:\n";
            echo "   Admin → Master Data → Jadwal Perkuliahan\n";
            echo "   Update: Dosen, Ruangan, Hari, Jam Mulai, Jam Selesai\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "\n❌ ERROR: {$e->getMessage()}\n";
        }
    }
}
