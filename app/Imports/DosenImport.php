<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Dosen;
use App\Models\ProgramStudi;
use App\Models\MataKuliah;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class DosenImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    protected $errors = [];
    protected $successCount = 0;
    protected $skipCount = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                // Skip empty rows
                if (empty($row['nidn']) || empty($row['nama_lengkap'])) {
                    $this->skipCount++;
                    continue;
                }

                // Check if NIDN already exists
                if (Dosen::where('nidn', $row['nidn'])->exists()) {
                    $this->errors[] = "Baris " . ($index + 2) . ": NIDN {$row['nidn']} sudah terdaftar";
                    $this->skipCount++;
                    continue;
                }

                // Check if username already exists
                if (User::where('username', $row['username'])->exists()) {
                    $this->errors[] = "Baris " . ($index + 2) . ": Username '{$row['username']}' sudah digunakan";
                    $this->skipCount++;
                    continue;
                }

                // Check if email already exists
                if (User::where('email', $row['email'])->exists()) {
                    $this->errors[] = "Baris " . ($index + 2) . ": Email '{$row['email']}' sudah digunakan";
                    $this->skipCount++;
                    continue;
                }

                // Parse Program Studi IDs (comma or semicolon separated kode_prodi)
                $programStudiIds = [];
                if (!empty($row['kode_prodi'])) {
                    $kodeProdis = array_map('trim', preg_split('/[,;]/', $row['kode_prodi']));
                    
                    foreach ($kodeProdis as $kodeProdi) {
                        $prodi = ProgramStudi::where('kode_prodi', $kodeProdi)
                            ->orWhere('nama_prodi', 'LIKE', '%' . $kodeProdi . '%')
                            ->first();
                        
                        if ($prodi) {
                            $programStudiIds[] = $prodi->id;
                        } else {
                            $this->errors[] = "Baris " . ($index + 2) . ": Program Studi '{$kodeProdi}' tidak ditemukan untuk NIDN {$row['nidn']}";
                        }
                    }
                }

                if (empty($programStudiIds)) {
                    $this->errors[] = "Baris " . ($index + 2) . ": Minimal 1 Program Studi harus valid untuk NIDN {$row['nidn']}";
                    $this->skipCount++;
                    continue;
                }

                // Use transaction for data consistency
                DB::beginTransaction();

                // Create User
                $user = User::create([
                    'username' => $row['username'],
                    'name' => trim(($row['gelar_depan'] ?? '') . ' ' . $row['nama_lengkap'] . ' ' . ($row['gelar_belakang'] ?? '')),
                    'email' => $row['email'],
                    'password' => Hash::make('dosen_staialfatih'),
                    'role' => 'dosen',
                    'is_active' => true,
                ]);

                // Create Dosen
                $dosen = Dosen::create([
                    'user_id' => $user->id,
                    'nidn' => $row['nidn'],
                    'nama_lengkap' => $row['nama_lengkap'],
                    'gelar_depan' => $row['gelar_depan'] ?? null,
                    'gelar_belakang' => $row['gelar_belakang'] ?? null,
                    'email' => $row['email'],
                    'no_telepon' => $row['no_telepon'] ?? null,
                ]);

                // Attach Program Studi (many-to-many relationship)
                $dosen->programStudis()->sync($programStudiIds);

                // Parse Mata Kuliah IDs (comma or semicolon separated kode_mk)
                $mataKuliahIds = [];
                if (!empty($row['kode_mk'])) {
                    $kodeMks = array_map('trim', preg_split('/[,;]/', $row['kode_mk']));
                    
                    foreach ($kodeMks as $kodeMk) {
                        if (empty($kodeMk)) continue; // Skip empty values
                        
                        $mk = MataKuliah::where('kode_mk', $kodeMk)->first();
                        
                        if ($mk) {
                            $mataKuliahIds[] = $mk->id;
                        } else {
                            // Just log warning, don't fail the import
                            $this->errors[] = "Baris " . ($index + 2) . ": Mata Kuliah '{$kodeMk}' tidak ditemukan untuk NIDN {$row['nidn']} (dilewati)";
                            Log::warning("MataKuliah not found: {$kodeMk} for dosen {$row['nidn']}");
                        }
                    }
                }

                // Attach Mata Kuliah (many-to-many relationship)
                if (!empty($mataKuliahIds)) {
                    $dosen->mataKuliahs()->sync($mataKuliahIds);
                    Log::info("Assigned " . count($mataKuliahIds) . " mata kuliah to dosen {$row['nidn']}");
                }

                DB::commit();
                $this->successCount++;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                $this->skipCount++;
                Log::error("Import error at row " . ($index + 2) . ": " . $e->getMessage());
            }
        }
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'nidn' => 'required|string|max:20',
            'nama_lengkap' => 'required|string|max:255',
            'kode_prodi' => 'required|string',
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getSkipCount()
    {
        return $this->skipCount;
    }

    public function onError(\Throwable $e)
    {
        // Errors are handled in collection method
    }
}
